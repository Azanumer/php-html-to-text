# PHP HTML to Text

Dependency-free HTML → readable plain text, for email fallbacks, search indexing, and CLI previews.

```php
require 'src/HtmlToText.php';

echo HtmlToText::convert('<h1>Hello</h1><p>Read the <a href="https://example.com/docs">docs</a>.</p>');
// Hello
//
// Read the docs (https://example.com/docs).
```

What it preserves:

- Paragraphs, headings, blockquotes → blank-line separated
- Lists → `- item` lines
- Links → `text (url)` (disable with `['linkUrls' => false]`)
- Tables → tab-separated rows
- Optional word wrap: `['maxLineLength' => 80]`

What it drops: `script`, `style`, `head`, and `noscript` content — never leaked into the text.

Run `php examples/html-to-text-example.php` for a demo.

## License

MIT
