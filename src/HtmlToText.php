<?php
/**
 * HtmlToText — dependency-free HTML → readable plain text.
 *
 * Preserves structure that matters in plain text: paragraphs, headings,
 * lists, links (as "text (url)"), tables (tab-separated rows), and
 * line breaks. Drops script/style/head content entirely.
 *
 * Usage: echo HtmlToText::convert($html);
 */

class HtmlToText
{
    public static function convert($html, $options = [])
    {
        $options = array_merge(['linkUrls' => true, 'maxLineLength' => 0], $options);

        // Drop content that must never appear in plain text.
        $html = preg_replace('#<(script|style|head|noscript)[^>]*>.*?</\1>#is', '', (string) $html);

        // Line breaks and block boundaries become newlines before stripping tags.
        $html = preg_replace('#<(br|hr)[^>]*>#i', "\n", $html);
        $html = preg_replace('#</(p|div|h[1-6]|li|tr|blockquote|pre|section|article|header|footer|table)>#i', "\n", $html);
        $html = preg_replace('#<li[^>]*>#i', "\n- ", $html);

        // Links: keep both text and URL, unless disabled.
        if ($options['linkUrls']) {
            $html = preg_replace_callback(
                '#<a\s[^>]*href=(["\'])(.*?)\1[^>]*>(.*?)</a>#is',
                function ($m) {
                    $text = trim(strip_tags($m[3]));
                    $url  = html_entity_decode(trim($m[2]), ENT_QUOTES, 'UTF-8');
                    if ($text === '' || $text === $url) {
                        return $url;
                    }
                    return $text . ' (' . $url . ')';
                },
                $html
            );
        }

        // Table cells become tab-separated; rows already end with \n.
        $html = preg_replace('#</t[dh]>#i', "\t", $html);

        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        // Collapse 3+ newlines, trim trailing spaces per line, trim ends.
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        $text = preg_replace('/[ \t]+$/m', '', $text);
        $text = preg_replace('/[ \t]{2,}/', ' ', $text);
        $text = trim($text);

        if ($options['maxLineLength'] > 0) {
            $text = wordwrap($text, (int) $options['maxLineLength']);
        }

        return $text;
    }
}
