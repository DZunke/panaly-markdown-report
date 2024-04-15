# Panaly - Project Analyzer - Markdown Report

The plugin to the [Panaly Project Analyzer](https://github.com/DZunke/panaly) delivers a markdown reporting. The rendering engine for the report
is currently very basic. It prints the date of the report and all metrics that are not of type `Integer` just as a
single line within the document. The `Integers` in a group are collected and printed as a table. 

## Example Report

```markdown
# Metric Report

> [!NOTE]
> This report was generated at `2024-04-11T16:00:03+00:00`

# First Title
| Metric         | Value |
|----------------|-------|
| A Metric       | 1     |
| Another Metric | 2000  |
| Wow! A Metric  | 12    |
```

## Example Configuration

```yaml
# panaly.dist.yaml
plugins:
    DZunke\PanalyMarkdownReport\MarkdownPlugin: ~ # no options available

reporting:
    markdown:
        targetFile: my-markdown-report.md
```

## Thanks and License

**Panaly Project Analyzer - Markdown Report** © 2024+, Denis Zunke. Released utilizing the [MIT License](https://mit-license.org/).

> GitHub [@dzunke](https://github.com/DZunke) &nbsp;&middot;&nbsp;
> Twitter [@DZunke](https://twitter.com/DZunke)
