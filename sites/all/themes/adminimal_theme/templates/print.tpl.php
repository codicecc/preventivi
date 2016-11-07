<?php

/**
 * @file
 * Default theme implementation to display a printer-friendly version page.
 *
 * This file is akin to Drupal's page.tpl.php template. The contents being
 * displayed are all included in the $content variable, while the rest of the
 * template focuses on positioning and theming the other page elements.
 *
 * All the variables available in the page.tpl.php template should also be
 * available in this template. In addition to those, the following variables
 * defined by the print module(s) are available:
 *
 * Arguments to the theme call:
 * - $node: The node object. For node content, this is a normal node object.
 *   For system-generated pages, this contains usually only the title, path
 *   and content elements.
 * - $format: The output format being used ('html' for the Web version, 'mail'
 *   for the send by email, 'pdf' for the PDF version, etc.).
 * - $expand_css: TRUE if the CSS used in the file should be provided as text
 *   instead of a list of @include directives.
 * - $message: The message included in the send by email version with the
 *   text provided by the sender of the email.
 *
 * Variables created in the preprocess stage:
 * - $print_logo: the image tag with the configured logo image.
 * - $content: the rendered HTML of the node content.
 * - $scripts: the HTML used to include the JavaScript files in the page head.
 * - $footer_scripts: the HTML  to include the JavaScript files in the page
 *   footer.
 * - $sourceurl_enabled: TRUE if the source URL infromation should be
 *   displayed.
 * - $url: absolute URL of the original source page.
 * - $source_url: absolute URL of the original source page, either as an alias
 *   or as a system path, as configured by the user.
 * - $cid: comment ID of the node being displayed.
 * - $print_title: the title of the page.
 * - $head: HTML contents of the head tag, provided by drupal_get_html_head().
 * - $robots_meta: meta tag with the configured robots directives.
 * - $css: the syle tags contaning the list of include directives or the full
 *   text of the files for inline CSS use.
 * - $sendtoprinter: depending on configuration, this is the script tag
 *   including the JavaScript to send the page to the printer and to close the
 *   window afterwards.
 *
 * print[--format][--node--content-type[--nodeid]].tpl.php
 *
 * The following suggestions can be used:
 * 1. print--format--node--content-type--nodeid.tpl.php
 * 2. print--format--node--content-type.tpl.php
 * 3. print--format.tpl.php
 * 4. print--node--content-type--nodeid.tpl.php
 * 5. print--node--content-type.tpl.php
 * 6. print.tpl.php
 *
 * Where format is the ouput format being used, content-type is the node's
 * content type and nodeid is the node's identifier (nid).
 *
 * @see print_preprocess_print()
 * @see theme_print_published
 * @see theme_print_breadcrumb
 * @see theme_print_footer
 * @see theme_print_sourceurl
 * @see theme_print_url_list
 * @see page.tpl.php
 * @ingroup print
 */
?>
<?php 
/**
* search all your field_collection
* @param $node the loaded node
* @param $collection the machine name of your field collection
* @return array of objects FieldCollectionItemEntity
*/
?>
<?php
$_SESSION["totale"]=0;
$tot=0;
$nodeid = 0;

$nodeid = $variables['node']->nid;

if($nodeid>0){
	$node = node_load($nodeid);
	$collection="field_articolo";
	foreach($node->{$collection}[LANGUAGE_NONE] as $collectionItem) {
			$entity = entity_load('field_collection_item', array($collectionItem['value']));
			$fieldCollection[] = $entity[(int)$collectionItem['value']];
	}
	foreach( $fieldCollection as $label => $value){
		$tempPre=0;
		$tempQua=0;
$tempSco=0;
		foreach($value as $l=>$v){
			if($l=="field_prezzo")$tempPre=(int)$v["und"][0]["value"];
			if($l=="field_quantit_")$tempQua=(int)$v["und"][0]["value"];
			if($l=="field_sconto")$tempSco=(int)$v["und"][0]["value"];			
		}
if($tempPre-$tempSco>0)$tot+=((int)($tempPre-$tempSco)*$tempQua);
	}
}
$_SESSION["totale"]=$tot;
$entity_field[0]['value'] = number_format($tot, 2, '.', '');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>">
  <head>
    <?php print $head; ?>
    <base href='<?php print $url ?>' />
    <title><?php print $print_title; ?></title>
    <?php print $scripts; ?>
    <?php if (isset($sendtoprinter)) print $sendtoprinter; ?>
    <?php print $robots_meta; ?>
    <?php if (theme_get_setting('toggle_favicon')): ?>
      <link rel='shortcut icon' href='<?php print theme_get_setting('favicon') ?>' type='image/x-icon' />
    <?php endif; ?>
    <?php print $css; ?>
    <style>
    .print-logo{float:left;height:100px;width:auto;}
    .field-collection-view-links,
    .action-links.action-links-field-collection-add{display:none;}
    .print-site_name{float:right;}
    .field-name-field-articolo .field{float:left;margin:0 2%}
		.field-name-field-codice{width:10%;}
		.field-name-field-descrizione{width:45%;}
		.field-name-field-prezzo{width:10%;}
		body.adminimal-skin-material fieldset {padding-top: 15px;}
		.print-content-totale{margin: 0 65% 1rem;font-size: 1.1rem;float: left;width: 50%;}
		.print-content-totale strong{float: left;margin: 0 0.2rem;}
		.field-collection-container {border-bottom: 0;}
		fieldset{border:0;margin:0;}
		.field-collection-view {padding: 0em 0 0.3em 0;}
		.field{margin: 0.5rem 0;}
    </style>
  </head>
  <body>
    <?php if (!empty($message)): ?>
      <div class="print-message"><?php //print $message; ?></div><p />
    <?php endif; ?>
    <?php if ($print_logo): ?>
      <div class="print-logo" style="height:100px;width:auto;float:left;"><?php print $print_logo; ?></div>
    <?php endif; ?>
    <div class="print-site_name" style="float:right;"><?php require(__DIR__."/customer-header.php");?></div>
    <p />
    <hr class="print-hr" />
    <?php if (!isset($node->type)): ?>
      <h2 class="print-title"><?php print $print_title; ?></h2>
    <?php endif; ?>
    <div class="print-content"><?php print $content; ?>
    <div class="print-content-totale"><strong>Totale: <?php echo number_format(($tot), 2, '.', '');?> €</strong></div>
    <div class="print-content-totale"><strong>Totale + IVA: <?php echo number_format(($tot*1.22), 2, '.', '');;?> €</strong></div>
    </div>
    <div class="print-footer"><?php print theme('print_footer'); ?>
    <br />
    <small>Si autorizza al trattamento dei dati personali ai sensi del dlgs 196/2003 e successive modificazioni</small></div>
    <hr class="print-hr" />
    <?php print $footer_scripts; ?>
  </body>
</html>
