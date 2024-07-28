# Weather forecast

## Introduction

Application for getting weather forecast with possibility of download Excel sheet (*xlsx file*). 
User inputs name of city and application returns him current forecast for given location.

User can also specify date of forecast to show. If user do not specify date, application shows results for current date.

**Note:**
Due to free API regulations application can show only forecast for next 5 days.

## Requirements
- Apache mod_rewrite.c library
- PHP 8.0~ (*tested on 8.2.0*)

## Installation

For correct work of an application load composer libraries first:

`composer install`

(*optional*)
Main root folder shold be set to `/public` folder
