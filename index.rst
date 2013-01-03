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
                sig_emit.push([1.8119812011719E-5, 2]);
        sig_emit.push([9.0599060058594E-6, 2]);
        sig_emit.push([1.4066696166992E-5, 4]);
        sig_emit.push([2.6226043701172E-5, 8]);
        sig_emit.push([5.2928924560547E-5, 16]);
        sig_emit.push([9.9897384643555E-5, 32]);
        sig_emit.push([0.0001838207244873, 64]);
        sig_emit.push([0.00036907196044922, 128]);
        sig_emit.push([0.00073504447937012, 256]);
        sig_emit.push([0.0014729499816895, 512]);
        sig_emit.push([0.0028531551361084, 1024]);
        sig_emit.push([0.0059292316436768, 2048]);
        sig_emit.push([0.011547088623047, 4096]);
        sig_emit.push([0.022461891174316, 8192]);
        sig_emit.push([0.046433210372925, 16384]);
        sig_inst.push([5.3167343139648E-5, 2]);
        sig_inst.push([4.7922134399414E-5, 4]);
        sig_inst.push([8.4877014160156E-5, 8]);
        sig_inst.push([0.00014305114746094, 16]);
        sig_inst.push([0.00027799606323242, 32]);
        sig_inst.push([0.00056886672973633, 64]);
        sig_inst.push([0.0011379718780518, 128]);
        sig_inst.push([0.0024139881134033, 256]);
        sig_inst.push([0.0047149658203125, 512]);
        sig_inst.push([0.0093870162963867, 1024]);
        sig_inst.push([0.019659996032715, 2048]);
        sig_inst.push([0.046744823455811, 4096]);
        sig_inst.push([0.10827398300171, 8192]);
        sig_inst.push([0.23542809486389, 16384]);
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

