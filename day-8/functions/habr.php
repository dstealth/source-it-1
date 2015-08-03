<?php
/**
 * Здесь хранятся функции связанные с Хабром.
 */

/**
 * Получить хабы с Хабра.
 *
 * @param string $baseUrl Base habr url.
 * @return array
 */
function getHubs($baseUrl)
{
    $hubs = [];

    $numberOfPagesIsReal = false;
    $numberOfPages = 1;
    $currentPage = 1;

    do {
        $contents = getHubsPageHtml($baseUrl, $currentPage);
        if ($contents) {
            $hubsFromPage = getHubsFromHtml($contents);
            $hubs = array_merge($hubs, $hubsFromPage);

            if (!$numberOfPagesIsReal) {
                $numberOfPages = getNumberOfPages($contents);
                $numberOfPagesIsReal = true;
            }
        }
        $currentPage++;
    } while($currentPage <= $numberOfPages);

    return $hubs;
}

/**
 * Получить html страницы хабов.
 *
 * @param string $baseUrl    Habr base url.
 * @param int    $pageNumber Page Number.
 * @return null|string
 */
function getHubsPageHtml($baseUrl, $pageNumber)
{
    $fullHubsUrl = getFullHubsUrl($baseUrl, $pageNumber);
    $contents = file_get_contents($fullHubsUrl);

    if ($contents) {
        return $contents;
    }
    return null;
}

/**
 * Получить url страницы хабов.
 *
 * @param string $baseUrl    Habr base url.
 * @param int    $pageNumber Page Number.
 * @return string
 */
function getFullHubsUrl($baseUrl, $pageNumber)
{
    return $baseUrl . '/hubs/page' . (int) $pageNumber;
}

/**
 * Получить хабы из содержимого страницы.
 *
 * @param string $contents Page contents.
 * @return array
 */
function getHubsFromHtml($contents)
{
    $hubs = [];

    $hubsHtmlPart = getHubsHtmlPart($contents);
    if ($hubsHtmlPart) {
        $hubsHtmlArray = splitHubsHtmlToArray($hubsHtmlPart);
        foreach ($hubsHtmlArray as $hubHtml) {
            $hubs[] = getHubData($hubHtml);
        }
    }
    return $hubs;
}

/**
 * Получить количество страниц из содержимого.
 *
 * @param string $pageContents Page Contents.
 * @return int|null
 */
function getNumberOfPages($pageContents)
{
    $pagerPattern = '/<ul\s+id\s*=\s*["\']nav-pages["\']>(.*?)<\/ul>/is';
    $hasPager = preg_match($pagerPattern, $pageContents, $match);
    if ($hasPager) {
        $liTags = $match[1];
        $contents = trim(strip_tags($liTags));
        $contents = preg_replace('/\s+/', ' ', $contents);

        $lastNumber = substr($contents, strrpos($contents, ' ') + 1);

        return (int) $lastNumber;
    }

    // Если пейджера нет, значит страница одна.
    return 1;
}

/**
 * Получить часть html с хабами из всего документа.
 *
 * @param string $pageContents Page contents.
 * @return string|null
 */
function getHubsHtmlPart($pageContents)
{
    $hubsPattern = '/<div.*?id\s*=\s*["\']hubs["\']>(.*?)<div\s+class\s*=\s*["\']page-nav["\']>/is';
    $hasHubs = preg_match($hubsPattern, $pageContents, $match);
    if ($hasHubs) {
        $hubsHtml = $match[1];
        return $hubsHtml;
    }

    return null;
}

/**
 * Разбить html с хабами на куски по одному хабу.
 *
 * @param string $hubsHtml Html with all hubs.
 * @return array
 */
function splitHubsHtmlToArray($hubsHtml)
{
    $hubsHtml = str_replace(["\n", "\r"], ' ', $hubsHtml);
    $habPattern = '/<div\s+class\s*=\s*["\']\s*hub\s*["\']\s*id\s*=\s*"hub_\d+"\s*>/is';
    $hubsHtmlArray = preg_split($habPattern, $hubsHtml);
    array_shift($hubsHtmlArray);

    return $hubsHtmlArray;
}

/**
 * Получить данные о хабе из куска html.
 *
 * @param string $hubHtml Hub html part.
 * @return array
 */
function getHubData($hubHtml)
{
    $hub = [];

    parseHubUrlAndTitle($hubHtml, $hub);
    parseHubIsProfiled($hubHtml, $hub);
    parseHubIndex($hubHtml, $hub);
    parseHubTags($hubHtml, $hub);

    return $hub;
}

/**
 * Получить и записать название и ссылку хаба.
 *
 * @param string $hubHtml Hub html part.
 * @param array  &$hub    Hub array.
 */
function parseHubUrlAndTitle($hubHtml, &$hub)
{
    $titlePattern = '/<div\s*class\s*=\s*["\']title["\']\s*>(.*?)<\/div>/';
    $hasTitle = preg_match($titlePattern, $hubHtml, $titleMatch);
    if ($hasTitle) {
        $titleHtml = $titleMatch[1];
        $hasUrl = preg_match('/href\s*=\s*["\'](.*?)["\']/', $titleHtml, $urlMatch);
        $hub['url'] = $hasUrl ? $urlMatch[1] : null;
        $hub['title'] = trim(strip_tags($titleHtml));
    }
}

/**
 * Получить и записать профильный ли хаб.
 *
 * @param string $hubHtml Hub html part.
 * @param array  &$hub    Hub array.
 */
function parseHubIsProfiled($hubHtml, &$hub)
{
    $hub['is_profiled'] = strpos($hubHtml, 'profiled_hub') !== false;
}

/**
 * Получить и записать индекс хаба.
 *
 * @param string $hubHtml Hub html part.
 * @param array  &$hub    Hub array.
 */
function parseHubIndex($hubHtml, &$hub)
{
    $indexPattern = '/<div\s+class\s*=\s*["\']habraindex["\']\s*>([0-9,]+)<\/div>/';
    $hasIndex = preg_match($indexPattern, $hubHtml, $indexMatch);
    if ($hasIndex) {
        $hub['index'] = (float) str_replace(',', '.', $indexMatch[1]);
    } else {
        $hub['index'] = null;
    }
}

/**
 * Получить и записать теги хаба.
 *
 * @param string $hubHtml Hub html part.
 * @param array  &$hub    Hub array.
 */
function parseHubTags($hubHtml, &$hub)
{
    $hub['tags'] = [];
    $tagsPattern = '/<div\s+class\s*=\s*["\']common_tags["\']\s*>(.*?)<\/div>/';
    $hasTags = preg_match($tagsPattern, $hubHtml, $tagsMatch);
    if ($hasTags) {
        preg_match_all('/href\s*=\s*["\'](.*?)["\'].*?>(.*?)<\/a>/is', $tagsMatch[1], $tagMatches, PREG_SET_ORDER);
        foreach ($tagMatches as $tagMatch) {
            $hub['tags'][] = [
                'url' => $tagMatch[1],
                'title' => $tagMatch[2]
            ];
        }
    }
}

/**
 * Save hubs to storage file.
 *
 * @param string $storagePath Storage file path.
 * @param array  $hubs        Hubs.
 * @return bool
 */
function saveHubs($storagePath, $hubs)
{
    return (bool) file_put_contents($storagePath, json_encode($hubs));
}