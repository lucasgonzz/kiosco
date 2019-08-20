<?php

function active($path){
	if(request()->is($path)){
		return 'active';
	}
}