<?php

return [

  /**
   * Should we send errors to Airbrake
   */
  'enabled'             => true,

  /**
   * Airbrake API key
   */
  'api_key'             => 'f5b2651d194d9e1d23306cd7246673dc',

  /**
   * Should we send errors async
   */
  'async'               => false,

  /**
   * Which enviroments should be ingored? (ex. local)
   */
  'ignore_environments' => [],

  /**
   * Ignore these exceptions
   */
  'ignore_exceptions'   => ['RecursiveDirectoryIterator'],

  /**
   * Connection to the airbrake server
   */
  'connection'          => [

    'host'      => 'errbit.m-dv.nl',

    'port'      => '80',

    'secure'    => false,

    'verifySSL' => false
  ]

];
