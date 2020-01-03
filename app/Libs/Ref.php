<?php

namespace App\Libs;

use App\Http\Requests;
use Illuminate\Support\Facades\Request;
use App\Tables\Blog;

class Ref
{
	
    public function __construct()
    {
        return $this;
    }
	
	public function replaceClickable($text)
	{
		$blogs = Blog::all();
		foreach($blogs as $blog){
			$text = preg_replace("/{$blog->name}/", "{{{LINK{$blog->id}LINK}}}", $text, 1);
		}
		foreach($blogs as $blog){
			$text = preg_replace("/{{{LINK{$blog->id}LINK}}}/", '['.$blog->name.']('.url('/').'/'.Request::segment(1).'/blog/'.preg_replace('/[^a-zA-Z0-9]/', '-', $blog->name).'/b'.$blog->id.')', $text, 1);
		}
		return $text;
	}
}
