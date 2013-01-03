.. ftp::

FTP Module
----------

The FTP Module provides Non-Blocking FTP transfers for XPSPL.

.. note::

    Currently only uploading files to a remote server is supported.

Installation
____________

The FTP Module is bundled with XPSPL as of version 3.0.0.

Requirements
%%%%%%%%%%%%

PHP
^^^

PHP FTP_ extension must be installed and enabled. 

.. _FTP: http://php.net/manual/en/book.ftp.php

XPSPL
^^^^^

XPSPL **>= 3.0**

Configuration
_____________

The FTP Module has no runtime configuration options available.

Usage
_____

Importing
%%%%%%%%%

.. code-block:: php

    import('ftp');

Uploading Files
%%%%%%%%%%%%%%%

.. code-block:: php

    import('ftp');

    $files = ['/tmp/myfile_1.txt', '/tmp/myfile_2.txt'];
    $server = [
        'hostname' => 'ftp.myhost.com',
        'username' => 'foo',
        'password' => 'bar'
    ];

    $upload = ftp\upload($files, $server);

    ftp\complete($upload, null_exhaust(function(){
        $file = $this->get_file();
        echo sprintf('%s has uploaded'.PHP_EOL,
            $file->get_name() 
        );
    }));

    ftp\failure($upload, null_exhaust(function(){
        $file = $this->get_file();
        echo sprintf('%s has failed to upload'.PHP_EOL,
            $file->get_name() 
        );
    }));
