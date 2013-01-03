.. prggmr documentation master file, created by
   sphinx-quickstart on Wed Dec 19 20:57:45 2012.

XPSPL - PHP Signal Processing Library
=====================================

XPSPL is a high performance signal processing environment for the PHP programming language.

.. note:: 

    XPSPL is not fully documented though it is production ready.

    If you are comfortable analyzing code enjoy the library and contribute to 
    the documentation to help those that come after us.

Source
------

XPSPL is hosted on Github_.

.. _Github: https://github.com/prggmr/XPSPL

Performance
-----------

The following performance tests were generated on a 2.7GHZ i5 processor using this script_.

.. _script: http://github.com/prggmr/XPSPL/tree/master/examples/performance.php

.. raw:: html

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var sig_inst = [['Time', 'Signals Installed']];
        var sig_emit = [['Time', 'Signals Emitted']];
         sig_emit.push([1.6927719116211E-5, 2]);
         sig_emit.push([2.5033950805664E-5, 4]);
         sig_emit.push([4.6968460083008E-5, 8]);
         sig_emit.push([8.8214874267578E-5, 16]);
         sig_emit.push([0.0001680850982666, 32]);
         sig_emit.push([0.00033402442932129, 64]);
         sig_emit.push([0.00065994262695312, 128]);
         sig_emit.push([0.0013110637664795, 256]);
         sig_emit.push([0.0025899410247803, 512]);
         sig_emit.push([0.0051929950714111, 1024]);
         sig_emit.push([0.010353088378906, 2048]);
         sig_emit.push([0.021350860595703, 4096]);
         sig_emit.push([0.042273044586182, 8192]);
         sig_emit.push([0.084069967269897, 16384]);
         sig_emit.push([0.16918182373047, 32768]);
         sig_inst.push([0.00013494491577148, 2]);
         sig_inst.push([7.5101852416992E-5, 4]);
         sig_inst.push([0.00014400482177734, 8]);
         sig_inst.push([0.00028204917907715, 16]);
         sig_inst.push([0.00057101249694824, 32]);
         sig_inst.push([0.0010921955108643, 64]);
         sig_inst.push([0.0021679401397705, 128]);
         sig_inst.push([0.0046241283416748, 256]);
         sig_inst.push([0.0093719959259033, 512]);
         sig_inst.push([0.019834995269775, 1024]);
         sig_inst.push([0.040457010269165, 2048]);
         sig_inst.push([0.095570087432861, 4096]);
         sig_inst.push([0.21990394592285, 8192]);
         sig_inst.push([0.54018092155457, 16384]);
         sig_inst.push([1.4461491107941, 32768]);
        var data_1 = google.visualization.arrayToDataTable(sig_emit);
        var data_2 = google.visualization.arrayToDataTable(sig_inst);
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data_1, {title: 'Signals Emitted'});
        var chart = new google.visualization.LineChart(document.getElementById('chart_div2'));
        chart.draw(data_2, {title: 'Signals Installed'});
      }
    </script>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
    <div id="chart_div2" style="width: 900px; height: 500px;"></div>

.. note::

   These tests were performed under in the in progress 3 unstable.

Author
------

XPSPL has been designed and developed by Nickolas C. Whiting.

Version
-------

XPSPL is currently in major version 3.

There is no current minor or bugfix release.

Support
-------

Support for XPSPL is offered through two support channels.

Mailing list
____________

A mailing list provided by Google Groups_.

.. _Groups: https://groups.google.com/forum/?fromgroups#!forum/prggmr


IRC
___

An IRC channel by irc.freenode.net ``#prggmr``.


Contents
--------

.. toctree::
   :maxdepth: 3
   :glob:

   docs/install
   docs/quickstart
   docs/configuration

Indices and tables
------------------

* :ref:`genindex`
* :ref:`search`

