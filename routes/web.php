<?php

use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');
Volt::route('/about', 'about')->name('about');
Volt::route('/legal', 'legal')->name('legal');
Volt::route('/contact', 'contact')->name('contact');
