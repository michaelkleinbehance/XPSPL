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
    
    <?php

    import('ftp');

Uploading Files
%%%%%%%%%%%%%%%

.. code-block:: php
    
    <?php

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

API
___

All functions and classes are under the ``ftp`` namespace.

.. function:: ftp\\upload(array $files, array $connection, [callable $callback = null])

   Performs a non-blocking FTP upload of the given file(s).

   When multiple files are given they will be uploaded simultaneously using 
   separate connections to the given ``$connection``.

   The ``$callback`` will be called once the files begin uploading.

   It is expected that the absolute path to the file will be given, failure to 
   do so will cause unexpected behavior.

   The connection array accepts,

   * **hostname** - Hostname of the server to upload.
   * **username** - Username to use when connecting.
   * **password** - Password to use when connecting.
   * **port** - Port number to connect on. *Default=21*
   * **timeout** - Connection timeout in seconds. *Default=90*

   :param array $files: Files to upload
   :param array $connection: FTP Connection options
   :param callable $callback: Function to call when upload begins
   :return object: SIG_Upload
