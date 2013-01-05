.. prggmr documentation master file, created by
   sphinx-quickstart on Wed Dec 19 20:57:45 2012.

XPSPL - PHP Signal Processing Library
=====================================

XPSPL is a high performance signal processing environment for the PHP programming language.

.. note:: 

    XPSPL is not fully documented though it is production ready.

    If you are comfortable analyzing code enjoy the library and contribute to 
    the documentation to help those that come after us.

Table of Contents
-----------------

.. toctree::
   :maxdepth: 2
   :glob:

   docs/install
   docs/configuration
   docs/quickstart
   docs/modules/ftp

Source
------

XPSPL is hosted on Github_.

.. _Github: https://github.com/prggmr/XPSPL

Performance
-----------

The following performance tests were generated on a 2.7GHZ i5 processor using this script_.

.. _script: https://github.com/prggmr/XPSPL/blob/event_removal/examples/performance.php

.. raw:: html

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart(){
      var ProcessesInstalled = [["Time", "Processes Installed"]];
      ProcessesInstalled.push([5.1975250244141E-5, 2]);
      ProcessesInstalled.push([4.5061111450195E-5, 4]);
      ProcessesInstalled.push([7.7009201049805E-5, 8]);
      ProcessesInstalled.push([0.00014400482177734, 16]);
      ProcessesInstalled.push([0.00027894973754883, 32]);
      ProcessesInstalled.push([0.00055313110351562, 64]);
      ProcessesInstalled.push([0.0012791156768799, 128]);
      ProcessesInstalled.push([0.0025739669799805, 256]);
      ProcessesInstalled.push([0.0046629905700684, 512]);
      ProcessesInstalled.push([0.0092661380767822, 1024]);
      ProcessesInstalled.push([0.019289970397949, 2048]);
      ProcessesInstalled.push([0.04413104057312, 4096]);
      var ProcessesInstalled_graph = google.visualization.arrayToDataTable(ProcessesInstalled);
      var ProcessesInstalled_chart = new google.visualization.LineChart(document.getElementById("ProcessesInstalled"));
      ProcessesInstalled_chart.draw(ProcessesInstalled_graph, {title: "Processes Installed"});
      var SignalsEmitted = [["Time", "Signals Emitted"]];
      SignalsEmitted.push([1.0013580322266E-5, 2]);
      SignalsEmitted.push([1.6927719116211E-5, 4]);
      SignalsEmitted.push([3.4809112548828E-5, 8]);
      SignalsEmitted.push([5.4121017456055E-5, 16]);
      SignalsEmitted.push([9.5129013061523E-5, 32]);
      SignalsEmitted.push([0.00022006034851074, 64]);
      SignalsEmitted.push([0.00041699409484863, 128]);
      SignalsEmitted.push([0.00073599815368652, 256]);
      SignalsEmitted.push([0.0014479160308838, 512]);
      SignalsEmitted.push([0.003018856048584, 1024]);
      SignalsEmitted.push([0.0059199333190918, 2048]);
      SignalsEmitted.push([0.013248920440674, 4096]);
      var SignalsEmitted_graph = google.visualization.arrayToDataTable(SignalsEmitted);
      var SignalsEmitted_chart = new google.visualization.LineChart(document.getElementById("SignalsEmitted"));
      SignalsEmitted_chart.draw(SignalsEmitted_graph, {title: "Signals Emitted"});
      var InterruptionsInstalled = [["Time", "Interruptions Installed"]];
      InterruptionsInstalled.push([3.6001205444336E-5, 2]);
      InterruptionsInstalled.push([3.0040740966797E-5, 4]);
      InterruptionsInstalled.push([5.4121017456055E-5, 8]);
      InterruptionsInstalled.push([0.00010800361633301, 16]);
      InterruptionsInstalled.push([0.00020599365234375, 32]);
      InterruptionsInstalled.push([0.00040197372436523, 64]);
      InterruptionsInstalled.push([0.00082302093505859, 128]);
      InterruptionsInstalled.push([0.0016410350799561, 256]);
      InterruptionsInstalled.push([0.0033121109008789, 512]);
      InterruptionsInstalled.push([0.0073161125183105, 1024]);
      InterruptionsInstalled.push([0.015161991119385, 2048]);
      InterruptionsInstalled.push([0.033018827438354, 4096]);
      var InterruptionsInstalled_graph = google.visualization.arrayToDataTable(InterruptionsInstalled);
      var InterruptionsInstalled_chart = new google.visualization.LineChart(document.getElementById("InterruptionsInstalled"));
      InterruptionsInstalled_chart.draw(InterruptionsInstalled_graph, {title: "Interruptions Installed"});
      var LoopsPerformed = [["Time", "Loops Performed"]];
      LoopsPerformed.push([2.1934509277344E-5, 2]);
      LoopsPerformed.push([4.0054321289062E-5, 4]);
      LoopsPerformed.push([7.7009201049805E-5, 8]);
      LoopsPerformed.push([0.00014901161193848, 16]);
      LoopsPerformed.push([0.00034093856811523, 32]);
      LoopsPerformed.push([0.0006411075592041, 64]);
      LoopsPerformed.push([0.0012891292572021, 128]);
      LoopsPerformed.push([0.0025918483734131, 256]);
      LoopsPerformed.push([0.0046830177307129, 512]);
      LoopsPerformed.push([0.0093960762023926, 1024]);
      LoopsPerformed.push([0.018944978713989, 2048]);
      LoopsPerformed.push([0.039000988006592, 4096]);
      var LoopsPerformed_graph = google.visualization.arrayToDataTable(LoopsPerformed);
      var LoopsPerformed_chart = new google.visualization.LineChart(document.getElementById("LoopsPerformed"));
      LoopsPerformed_chart.draw(LoopsPerformed_graph, {title: "Loops Performed"});
    }
    </script>
    <div id="ProcessesInstalled" style="width: 450px; height: 250px; float:left;"></div><div id="SignalsEmitted" style="width: 450px; height: 250px; float:left;"></div><div id="InterruptionsInstalled" style="width: 450px; height: 250px; float:left;"></div><div id="LoopsPerformed" style="width: 450px; height: 250px; float:left;"></div>
    <div style="clear: both;"></div>
   
.. note::

   These tests were performed under the event_removal_ branch.

.. _event_removal: http://github.com/prggmr/XPSPL/tree/event_removal

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

Search
------

* :ref:`search`

