<?php
// Dokeos - elearning and course management software
// See license terms in dokeos/documentation/license.txt

// Training tools
// Course (learning path) - documents

// For more information: http://docs.fckeditor.net/FCKeditor_2.x/Developers_Guide/Configuration/Configuration_Options

// This is the visible toolbar set when the editor has "normal" size.
$config['ToolbarSets']['Normal'] = array(
	array('PasteWord','-','Undo','Redo'),
	array('Link','Unlink','Anchor','Glossary'),
	array('Image','videoPlayer','MP3','mimetex','asciimath','Table','SpecialChar'),
	array('Outdent','Indent','TextColor','BGColor','-','OrderedList','UnorderedList','JustifyLeft','JustifyCenter','JustifyRight'),
	'/',
	array('Style','FontFormat','FontName','FontSize'),
	array('Bold','Italic','Underline','-','Source'),
);//save, FitWindow don't run well here

// This is the visible toolbar set when the editor is maximized.
// If it has not been defined, then the toolbar set for the "normal" size is used.
$config['ToolbarSets']['Maximized'] = array(
	array('FitWindow','DocProps','-','Save','NewPage','Preview','-','Templates'),
	array('Cut','Copy','Paste','PasteText','PasteWord','-','Print'),
	array('Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
	array('Link','Unlink','Anchor','Glossary'),
	'/',
	array('Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'),
	array('OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'),
	array('JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'),
	array('Rule','SpecialChar','PageBreak'),
	array('mimetex','asciimath','Image','imgmapPopup','-','MP3','-','videoPlayer','-','googlemaps','Smiley'),
	'/',
	array('Style','FontFormat','FontName','FontSize'),
	array('TextColor','BGColor'),
	array('Table','TableInsertRowAfter','TableDeleteRows','TableInsertColumnAfter','TableDeleteColumns','TableInsertCellAfter','TableDeleteCells','TableMergeCells','TableHorizontalSplitCell','TableVerticalSplitCell','TableCellProp'),
	array('ShowBlocks','Source')
);

// Sets whether the toolbar can be collapsed/expanded or not.
// Possible values: true , false
//$config['ToolbarCanCollapse'] = true;

// Sets how the editor's toolbar should start - expanded or collapsed.
// Possible values: true , false
//$config['ToolbarStartExpanded'] = true;

//This option sets the location of the toolbar.
// Possible values: 'In' , 'None' , 'Out:[TargetId]' , 'Out:[TargetWindow]([TargetId])'
//$config['ToolbarLocation'] = 'In';

// A setting for blocking copy/paste functions of the editor.
// This setting activates on leaners only. For users with other statuses there is no blocking copy/paste.
// Possible values: true , false
//$config['BlockCopyPaste'] = false;

// Here new width and height of the editor may be set.
// Possible values, examples: 300 , '250' , '100%' , ...
//$config['Width'] = '100%';
//$config['Height'] = '700';
