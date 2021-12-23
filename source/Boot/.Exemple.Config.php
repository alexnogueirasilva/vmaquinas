<?php
/**
 * DATABASE
 */
const CONF_DB_HOST = "";
const CONF_DB_USER = "";
const CONF_DB_PASS = "";
const CONF_DB_NAME = "";

/**
 * PROJECT URLs
 */
const CONF_URL_BASE = "https://www.moneycoffee.com.br";
const CONF_URL_TEST = "http://localhost/fsphp";
const CONF_URL_ADMIN = "/admin";

/**
 * SITE
 */
const CONF_SITE_NAME = "MoneyCoffee";
const CONF_SITE_TITLE = "Gerencie suas contas com o melhor café";
const CONF_SITE_DESC = "O MoneyCoffee é um gerenciador de contas simples, poderoso e gratuito. O prazer de tomar um café e ter o controle total de suas contas.";
const CONF_SITE_LANG = "pt_BR";
const CONF_SITE_DOMAIN = "moneycoffee.com.br";

/**
 * SOCIAL
 */
const CONF_SOCIAL_TWITTER_CREATOR = "@DevOnCycle";
const CONF_SOCIAL_TWITTER_PUBLISHER = "@DevOnCycle";
const CONF_SOCIAL_FACEBOOK_APP = "626590460695980";
const CONF_SOCIAL_FACEBOOK_PAGE = "devaction";
const CONF_SOCIAL_FACEBOOK_AUTHOR = "devaction";
const CONF_SOCIAL_GOOGLE_PAGE = "107305124528362639842";
const CONF_SOCIAL_GOOGLE_AUTHOR = "103958419096641225872";
const CONF_SOCIAL_INSTAGRAM_PAGE = "alexnogueirasilva";
const CONF_SOCIAL_YOUTUBE_PAGE = "devaction";

/**
 * DATES
 */
const CONF_DATE_BR = "d/m/Y H:i:s";
const CONF_DATE_APP = "Y-m-d H:i:s";

/**
 * PASSWORD
 */
const CONF_PASSWD_MIN_LEN = 8;
const CONF_PASSWD_MAX_LEN = 40;
const CONF_PASSWD_ALGO = PASSWORD_DEFAULT;
const CONF_PASSWD_OPTION = ["cost" => 10];

/**
 * VIEW
 */
const CONF_VIEW_PATH = __DIR__ . "/../../shared/views";
const CONF_VIEW_EXT = "php";
const CONF_VIEW_THEME = "cafeweb";
const CONF_VIEW_APP = "cafeapp";

/**
 * UPLOAD
 */
const CONF_UPLOAD_DIR = "storage";
const CONF_UPLOAD_IMAGE_DIR = "images";
const CONF_UPLOAD_FILE_DIR = "files";
const CONF_UPLOAD_MEDIA_DIR = "medias";

/**
 * IMAGES
 */
const CONF_IMAGE_CACHE = CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache";
const CONF_IMAGE_SIZE = 2000;
const CONF_IMAGE_QUALITY = ["jpg" => 75, "png" => 5];

/**
 * MAIL
 */
const CONF_MAIL_HOST = "smtp.sendgrid.net";
const CONF_MAIL_PORT = "587";
const CONF_MAIL_USER = "apikey";
const CONF_MAIL_PASS = "";
const CONF_MAIL_SENDER = ["name" => "Alex N. Silva", "address" => "alex@devaction.com.br"];
const CONF_MAIL_SUPPORT = "alex@devaction.com.br";
const CONF_MAIL_OPTION_LANG = "br";
const CONF_MAIL_OPTION_HTML = true;
const CONF_MAIL_OPTION_AUTH = true;
const CONF_MAIL_OPTION_SECURE = "";
const CONF_MAIL_OPTION_CHARSET = "utf-8";
const CONF_SITE_ADDR_STREET = "Rua: Trigêmeos";
const CONF_SITE_ADDR_NUMBER = "0";
const CONF_SITE_ADDR_COMPLEMENT = "Rua do México";
const CONF_SITE_ADDR_CITY = "Feira de Santana";
const CONF_SITE_ADDR_STATE = "Bahia";
const CONF_SITE_ADDR_ZIPCODE = "44090-778";
