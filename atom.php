<?xml version='1.0' encoding='UTF-8'?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
    <title><?= htmlspecialchars($title) ?></title>
    <link><?= htmlspecialchars($link) ?></link>
    <description></description>
    <language>ru</language>
    <ttl>600</ttl>
    <?php foreach ($items as $item): ?>
    <item>
        <title><?= htmlspecialchars($item['title']) ?></title>
        <link><?= htmlspecialchars($item['link']) ?></link>
        <pubDate><?=  $item['date']->format('r') ?></pubDate>
        <guid isPermaLink="false"><?= htmlspecialchars($item['guid']) ?></guid>
        <?php if (!empty($item['author'])): ?>
        <author><?= htmlspecialchars($item['author']) ?></author>
        <?php endif; ?>
        <description><?= htmlspecialchars($item['description']) ?></description>
    </item>
    <?php endforeach; ?>
    </channel>
</rss>
