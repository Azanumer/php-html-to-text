<?php
require __DIR__ . '/../src/HtmlToText.php';

$html = '<h1>Hello</h1><p>Read the <a href="https://example.com/docs">docs</a> first.</p>'
    . '<ul><li>One</li><li>Two</li></ul>'
    . '<script>alert("never shown");</script>';

echo HtmlToText::convert($html) . PHP_EOL;
