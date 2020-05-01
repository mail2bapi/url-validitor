<?php
/**
 * Author: Bapi Roy <mail2bapi@astrosoft.co.in>
 * Date: 30/04/20 9:17 PM
 * File: Validator.php
 */

namespace Mail2bapi\UrlValidator;


class Validator
{
    /**
     * Check domain validity by looking up A records
     * @param string $domain
     * @return bool
     */
    public static function validDomain(string $domain): bool
    {
        return  checkdnsrr($domain , "A");;
    }

    /**
     * Check to Valid URL
     * @param string $url
     * @return bool
     */
    public static function validUrl(string $url): bool
    {
        $urlContents = parse_url(trim($url));
        if(self::validDomain($urlContents['host'])) {
            $path = isset($urlContents['path']) ? $urlContents['path'] : null;
            $urlPaths = explode("/", $path);
            $encodedPath = '';
            foreach ($urlPaths as $urlPath) {
                if (!empty($urlPath)) {
                    $encodedPath .= '/' . urlencode($urlPath);
                }
            }
            $encodedPath .= '/';

            $urlContents['query'] = isset($urlContents['query']) ? urlencode($urlContents['query']) : null;
            $urlContents['fragment'] = isset($urlContents['fragment']) ? urlencode($urlContents['fragment']) : null;

            $url = $urlContents['scheme'] . '://' . $urlContents['host'] . ((!empty($urlContents['port'])) ? ':' . $urlContents['port'] : '') . $encodedPath . $urlContents['query'] . ((!empty($urlContents['fragment'])) ? '#' . $urlContents['fragment'] : '');

            return (filter_var($url, FILTER_VALIDATE_URL));
        }
        return false;
    }
}