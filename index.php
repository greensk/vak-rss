<?php
$mainUrl = "http://vak.ed.gov.ru";
$spec = @$_GET['spec'];
$toDate = rawurldecode(strftime('%Y-%m-%d', time() + 3*30*24*60*60));
$fromDate = rawurldecode(strftime('%Y-%m-%d', time() - 30*24*60*60));
$url = "$mainUrl/ais/vak/templates/vak_idc.list.php?cmd=*WHERE%20(a.shifr_nauch_spec%20%3D%20%3F)%20AND%20(a.date_zach%20%3C%3D%20%3F)%20AND%20(a.date_zach%20%3E%3D%20%3F)&args[]=$spec&args[]=$toDate&args[]=$fromDate";

$mainContent = file_get_contents($url);

$title = "Объявления о защитах диссертаций по специальности $spec";
$link = "http://vak.greendocs.ru/rss/?spec=$spec";

preg_match_all('/\/dis\-details\?xPARAM\=(\d+)/', $mainContent, $matches);

$items = [];

foreach ($matches[1] as $id) {
    $dissUrl = "http://vak.ed.gov.ru/ais/vak/idc2/$id";
    $dissContent = file_get_contents($dissUrl);
    $dom = new DOMDocument;
    @$dom->loadHTML($dissContent);

    $xpath = new DOMXPath($dom);
    $entries = $xpath->query('//*[@class="dissertation"]');
    $description = $dom->saveHTML($entries->item(0));
    
    $items[] = [
        'guid' => $id,
        'link' => "http://vak.ed.gov.ru/dis-details?xPARAM=$id",
        'description' => $description,
        'title' => fetchValue($dom, 'Название темы диссертации'),
        'author' => fetchValue($dom, 'Фамилия, имя, отчество соискателя'),
        'date' => DateTime::createFromFormat('d.m.Y', fetchValue($dom, 'Дата первичной  публикации объявления')),
    ];
}

include 'atom.php';

function fetchValue ($dom, $name) {
    $xpath = new DOMXPath($dom);
    $entries = $xpath->query('//em[string()="' . $name .  '"]/../../td[2]');
    if ($entries->length > 0) {
        return $entries->item(0)->textContent;
    } else {
        return null;
    }
}
