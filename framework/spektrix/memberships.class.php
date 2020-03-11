<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

class Memberships extends Spektrix
{
	public $id;
	public $name;
	public $description;
	public $html_description;
	public $imgurl;
	public $thumbnailurl;
	public $attributes;

	function __construct($membership)
	{
		$this->id = (integer) $membership->id;
		$this->name = (string) $membership->Name;
		$this->description = (string) $membership->Description;
		$this->html_description = (string) $membership->HtmlDescription;
		$this->imgurl = (string) $membership->ImageUrl;
		$this->thumbnailurl = (string) $membership->ThumbnailUrl;
		$this->attributes = (string) $membership->Attributes;
	}
}
