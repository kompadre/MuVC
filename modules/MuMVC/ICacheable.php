<?php
namespace MuMVC;

interface ICacheable 
{
	public function cacheSave();
	public function cacheLoad($data);
}