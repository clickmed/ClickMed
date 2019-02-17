<?php
/**
 * Manager and display megamenu use bootstrap framework
 *
 * @package   dormegamenu
 * @version   1.0.0
 * @author    http://www.doradothemes@gmail.com
 * @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
 *               <info@doradothemes@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class DorMegamenuWidgetVideocode extends DorMegamenuWidgetBase {

	public $name = 'videocode';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Video Code'), 'explain' => 'Make Video widget via putting Youtube Code, Vimeo Code');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();

		$input_fields = array(
			array(
				'type' => 'text',
				'label' => $this->l('Video Link'),
				'name' => 'video_link',
				'default' => 'http://www.youtube.com/watch?v=lzY4lkT8ElU',
				'autoload_rte' => false,
				'desc' => $this->l('Copy Youtube link or vimeo link and put here')
			),
			array(
				'type' => 'text',
				'label' => $this->l('Width'),
				'name' => 'width',
				'default' => '100%',
				'autoload_rte' => false,
				'desc' => $this->l('Enter Video Width in numberic (300) or percentage (100%)')
			),
			array(
				'type' => 'text',
				'label' => $this->l('Height'),
				'name' => 'height',
				'default' => '300',
				'autoload_rte' => false,
				'desc' => $this->l('Enter Video Width in numberic (300) or percentage (100%)')
			),
		);
		$fields = array_merge($this->input_fields, $input_fields, $this->input_fields_end);
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Widget Infomation'),
			),
			'input' => $fields
		);
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues($data),
			'languages' => Context::getContext()->controller->getLanguages(),
			'id_language' => $default_lang
		);

		return $helper->generateForm($this->fields_form);
	}

	public function getYoubetuID($url)
	{
		$pattern = '%^# Match any youtube URL
		        (?:https?://)?  # Optional scheme. Either http or https
		        (?:www\.)?      # Optional www subdomain
		        (?:             # Group host alternatives
		          youtu\.be/    # Either youtu.be,
		        | youtube\.com  # or youtube.com
		          (?:           # Group path alternatives
		            /embed/     # Either /embed/
		          | /v/         # or /v/
		          | /watch\?v=  # or /watch\?v=
		       )             # End path alternatives.
		     )               # End host alternatives.
		        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
		        $%x';
		$result = preg_match($pattern, $url, $matches);
		if (false !== $result)
			return $matches[1];
		return false;
	}

	public function getHostInfo($vid_link)
	{
		// youtube get video id
		if (preg_match('#youtu#', $vid_link))
			return array('host_name' => 'youtube', 'original_key' => $this->getYoubetuID($vid_link));
		// vimeo get video id
		elseif (preg_match('#vimeo#', $vid_link))
			if (preg_match('#(?<=/)([\d]+)#', $vid_link, $matches))
				return array('host_name' => 'vimeo', 'original_key' => $matches[0]);

		return false;
	}

	public function renderContent($setting)
	{
		$t = array(
			'name' => '',
			'video_link' => '',
			'width' => '100%',
			'height' => 300
		);
		$setting = array_merge($t, $setting);

		$video = $this->getHostInfo($setting['video_link']);

		$setting['video_link'] = '';

		if (isset($video['host_name']))
		{
			$setting['video_link'] = $video['host_name'] == 'youtube' ? '//www.youtube.com/embed/' : '//player.vimeo.com/video/';
			$setting['video_link'] .= $video['original_key'];
		}
		$output = array('type' => 'videocode', 'data' => $setting);
		return $output;
	}

}