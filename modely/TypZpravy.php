<?php
enum TypZpravy : string
{
    case Info = 'alert alert-primary text-left';
    case Uspech = 'alert alert-success text-left';
    case Chyba = 'alert alert-danger text-left';
}