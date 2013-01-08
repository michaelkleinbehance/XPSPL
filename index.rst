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
   docs/api
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
   ProcessesInstalled.push([4.9114227294922E-5, 2]);
   ProcessesInstalled.push([4.6014785766602E-5, 4]);
   ProcessesInstalled.push([8.6069107055664E-5, 8]);
   ProcessesInstalled.push([0.00014090538024902, 16]);
   ProcessesInstalled.push([0.00026202201843262, 32]);
   ProcessesInstalled.push([0.00051617622375488, 64]);
   ProcessesInstalled.push([0.001107931137085, 128]);
   ProcessesInstalled.push([0.0021240711212158, 256]);
   ProcessesInstalled.push([0.0043179988861084, 512]);
   ProcessesInstalled.push([0.0086948871612549, 1024]);
   ProcessesInstalled.push([0.017237901687622, 2048]);
   ProcessesInstalled.push([0.043201923370361, 4096]);
   ProcessesInstalled.push([0.097223043441772, 8192]);
   var ProcessesInstalled_graph = google.visualization.arrayToDataTable(ProcessesInstalled);
   var ProcessesInstalled_chart = new google.visualization.LineChart(document.getElementById("ProcessesInstalled"));
   ProcessesInstalled_chart.draw(ProcessesInstalled_graph, {title: "Processes Installed"});
   var SignalsEmitted = [["Time", "Signals Emitted"]];
   SignalsEmitted.push([8.1062316894531E-6, 2]);
   SignalsEmitted.push([1.3113021850586E-5, 4]);
   SignalsEmitted.push([2.4080276489258E-5, 8]);
   SignalsEmitted.push([4.6968460083008E-5, 16]);
   SignalsEmitted.push([9.3221664428711E-5, 32]);
   SignalsEmitted.push([0.00018596649169922, 64]);
   SignalsEmitted.push([0.00037002563476562, 128]);
   SignalsEmitted.push([0.00074386596679688, 256]);
   SignalsEmitted.push([0.0014829635620117, 512]);
   SignalsEmitted.push([0.0029439926147461, 1024]);
   SignalsEmitted.push([0.0065841674804688, 2048]);
   SignalsEmitted.push([0.011877059936523, 4096]);
   SignalsEmitted.push([0.024363994598389, 8192]);
   var SignalsEmitted_graph = google.visualization.arrayToDataTable(SignalsEmitted);
   var SignalsEmitted_chart = new google.visualization.LineChart(document.getElementById("SignalsEmitted"));
   SignalsEmitted_chart.draw(SignalsEmitted_graph, {title: "Signals Emitted"});
   var SignalRegistration = [["Time", "Signal Registration"]];
   SignalRegistration.push([2.5033950805664E-5, 2]);
   SignalRegistration.push([1.7881393432617E-5, 4]);
   SignalRegistration.push([3.1948089599609E-5, 8]);
   SignalRegistration.push([6.0081481933594E-5, 16]);
   SignalRegistration.push([0.00011682510375977, 32]);
   SignalRegistration.push([0.0002288818359375, 64]);
   SignalRegistration.push([0.00049901008605957, 128]);
   SignalRegistration.push([0.00093889236450195, 256]);
   SignalRegistration.push([0.001945972442627, 512]);
   SignalRegistration.push([0.0037140846252441, 1024]);
   SignalRegistration.push([0.0075948238372803, 2048]);
   SignalRegistration.push([0.017052888870239, 4096]);
   SignalRegistration.push([0.040914058685303, 8192]);
   var SignalRegistration_graph = google.visualization.arrayToDataTable(SignalRegistration);
   var SignalRegistration_chart = new google.visualization.LineChart(document.getElementById("SignalRegistration"));
   SignalRegistration_chart.draw(SignalRegistration_graph, {title: "Signal Registration"});
   var ListnersInstalled = [["Time", "Listners Installed"]];
   ListnersInstalled.push([0.00013399124145508, 2]);
   ListnersInstalled.push([8.1062316894531E-5, 4]);
   ListnersInstalled.push([0.00014686584472656, 8]);
   ListnersInstalled.push([0.00026321411132812, 16]);
   ListnersInstalled.push([0.00049805641174316, 32]);
   ListnersInstalled.push([0.00094509124755859, 64]);
   ListnersInstalled.push([0.0027999877929688, 128]);
   ListnersInstalled.push([0.0037438869476318, 256]);
   ListnersInstalled.push([0.0071589946746826, 512]);
   ListnersInstalled.push([0.018350839614868, 1024]);
   ListnersInstalled.push([0.032799959182739, 2048]);
   ListnersInstalled.push([0.074815988540649, 4096]);
   ListnersInstalled.push([0.14480090141296, 8192]);
   var ListnersInstalled_graph = google.visualization.arrayToDataTable(ListnersInstalled);
   var ListnersInstalled_chart = new google.visualization.LineChart(document.getElementById("ListnersInstalled"));
   ListnersInstalled_chart.draw(ListnersInstalled_graph, {title: "Listners Installed"});
   var InterruptionsInstalled = [["Time", "Interruptions Installed"]];
   InterruptionsInstalled.push([4.1961669921875E-5, 2]);
   InterruptionsInstalled.push([2.9087066650391E-5, 4]);
   InterruptionsInstalled.push([6.1988830566406E-5, 8]);
   InterruptionsInstalled.push([0.00012397766113281, 16]);
   InterruptionsInstalled.push([0.00020790100097656, 32]);
   InterruptionsInstalled.push([0.00038003921508789, 64]);
   InterruptionsInstalled.push([0.00074315071105957, 128]);
   InterruptionsInstalled.push([0.0014770030975342, 256]);
   InterruptionsInstalled.push([0.0029349327087402, 512]);
   InterruptionsInstalled.push([0.0058209896087646, 1024]);
   InterruptionsInstalled.push([0.011815071105957, 2048]);
   InterruptionsInstalled.push([0.026838064193726, 4096]);
   InterruptionsInstalled.push([0.062404155731201, 8192]);
   var InterruptionsInstalled_graph = google.visualization.arrayToDataTable(InterruptionsInstalled);
   var InterruptionsInstalled_chart = new google.visualization.LineChart(document.getElementById("InterruptionsInstalled"));
   InterruptionsInstalled_chart.draw(InterruptionsInstalled_graph, {title: "Interruptions Installed"});
   var LoopsPerformed = [["Time", "Loops Performed"]];
   LoopsPerformed.push([2.4080276489258E-5, 2]);
   LoopsPerformed.push([4.3153762817383E-5, 4]);
   LoopsPerformed.push([0.00012302398681641, 8]);
   LoopsPerformed.push([0.00018405914306641, 16]);
   LoopsPerformed.push([0.00034785270690918, 32]);
   LoopsPerformed.push([0.00066900253295898, 64]);
   LoopsPerformed.push([0.0013589859008789, 128]);
   LoopsPerformed.push([0.0026278495788574, 256]);
   LoopsPerformed.push([0.0054290294647217, 512]);
   LoopsPerformed.push([0.010776996612549, 1024]);
   LoopsPerformed.push([0.023216962814331, 2048]);
   LoopsPerformed.push([0.044018983840942, 4096]);
   LoopsPerformed.push([0.092760801315308, 8192]);
   var LoopsPerformed_graph = google.visualization.arrayToDataTable(LoopsPerformed);
   var LoopsPerformed_chart = new google.visualization.LineChart(document.getElementById("LoopsPerformed"));
   LoopsPerformed_chart.draw(LoopsPerformed_graph, {title: "Loops Performed"});
   var Interruptionbeforeemit = [["Time", "Interruption before emit"]];
   Interruptionbeforeemit.push([5.6982040405273E-5, 2]);
   Interruptionbeforeemit.push([5.1975250244141E-5, 4]);
   Interruptionbeforeemit.push([0.00011205673217773, 8]);
   Interruptionbeforeemit.push([0.00016903877258301, 16]);
   Interruptionbeforeemit.push([0.00030803680419922, 32]);
   Interruptionbeforeemit.push([0.0006101131439209, 64]);
   Interruptionbeforeemit.push([0.0012040138244629, 128]);
   Interruptionbeforeemit.push([0.0024490356445312, 256]);
   Interruptionbeforeemit.push([0.0048339366912842, 512]);
   Interruptionbeforeemit.push([0.010067939758301, 1024]);
   Interruptionbeforeemit.push([0.019644021987915, 2048]);
   Interruptionbeforeemit.push([0.044479131698608, 4096]);
   Interruptionbeforeemit.push([0.094290971755981, 8192]);
   var Interruptionbeforeemit_graph = google.visualization.arrayToDataTable(Interruptionbeforeemit);
   var Interruptionbeforeemit_chart = new google.visualization.LineChart(document.getElementById("Interruptionbeforeemit"));
   Interruptionbeforeemit_chart.draw(Interruptionbeforeemit_graph, {title: "Interruption before emit"});
   var Interruptionafteremit = [["Time", "Interruption after emit"]];
   Interruptionafteremit.push([6.1988830566406E-5, 2]);
   Interruptionafteremit.push([4.0054321289062E-5, 4]);
   Interruptionafteremit.push([0.00010204315185547, 8]);
   Interruptionafteremit.push([0.00019097328186035, 16]);
   Interruptionafteremit.push([0.00029492378234863, 32]);
   Interruptionafteremit.push([0.0006098747253418, 64]);
   Interruptionafteremit.push([0.0012338161468506, 128]);
   Interruptionafteremit.push([0.0024018287658691, 256]);
   Interruptionafteremit.push([0.0047378540039062, 512]);
   Interruptionafteremit.push([0.0094130039215088, 1024]);
   Interruptionafteremit.push([0.019160985946655, 2048]);
   Interruptionafteremit.push([0.043645858764648, 4096]);
   Interruptionafteremit.push([0.090873956680298, 8192]);
   var Interruptionafteremit_graph = google.visualization.arrayToDataTable(Interruptionafteremit);
   var Interruptionafteremit_chart = new google.visualization.LineChart(document.getElementById("Interruptionafteremit"));
   Interruptionafteremit_chart.draw(Interruptionafteremit_graph, {title: "Interruption after emit"});
   var ComplexSignalRegistration = [["Time", "Complex Signal Registration"]];
   ComplexSignalRegistration.push([3.0994415283203E-5, 2]);
   ComplexSignalRegistration.push([1.7166137695312E-5, 4]);
   ComplexSignalRegistration.push([3.7908554077148E-5, 8]);
   ComplexSignalRegistration.push([8.8930130004883E-5, 16]);
   ComplexSignalRegistration.push([0.00015115737915039, 32]);
   ComplexSignalRegistration.push([0.00023198127746582, 64]);
   ComplexSignalRegistration.push([0.00046420097351074, 128]);
   ComplexSignalRegistration.push([0.00093293190002441, 256]);
   ComplexSignalRegistration.push([0.001878023147583, 512]);
   ComplexSignalRegistration.push([0.003882884979248, 1024]);
   ComplexSignalRegistration.push([0.0075020790100098, 2048]);
   ComplexSignalRegistration.push([0.020736217498779, 4096]);
   ComplexSignalRegistration.push([0.043452024459839, 8192]);
   var ComplexSignalRegistration_graph = google.visualization.arrayToDataTable(ComplexSignalRegistration);
   var ComplexSignalRegistration_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalRegistration"));
   ComplexSignalRegistration_chart.draw(ComplexSignalRegistration_graph, {title: "Complex Signal Registration"});
   var ComplexSignalEvaluation = [["Time", "Complex Signal Evaluation"]];
   ComplexSignalEvaluation.push([1.0013580322266E-5, 2]);
   ComplexSignalEvaluation.push([2.1934509277344E-5, 4]);
   ComplexSignalEvaluation.push([3.6001205444336E-5, 8]);
   ComplexSignalEvaluation.push([5.9843063354492E-5, 16]);
   ComplexSignalEvaluation.push([0.00013399124145508, 32]);
   ComplexSignalEvaluation.push([0.00023698806762695, 64]);
   ComplexSignalEvaluation.push([0.00044393539428711, 128]);
   ComplexSignalEvaluation.push([0.00084090232849121, 256]);
   ComplexSignalEvaluation.push([0.0017180442810059, 512]);
   ComplexSignalEvaluation.push([0.0031960010528564, 1024]);
   ComplexSignalEvaluation.push([0.0056400299072266, 2048]);
   ComplexSignalEvaluation.push([0.011868000030518, 4096]);
   ComplexSignalEvaluation.push([0.022739171981812, 8192]);
   var ComplexSignalEvaluation_graph = google.visualization.arrayToDataTable(ComplexSignalEvaluation);
   var ComplexSignalEvaluation_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalEvaluation"));
   ComplexSignalEvaluation_chart.draw(ComplexSignalEvaluation_graph, {title: "Complex Signal Evaluation"});
   var ComplexSignalInterruptionBeforeInstall = [["Time", "Complex Signal Interruption Before Install"]];
   ComplexSignalInterruptionBeforeInstall.push([3.504753112793E-5, 2]);
   ComplexSignalInterruptionBeforeInstall.push([3.4093856811523E-5, 4]);
   ComplexSignalInterruptionBeforeInstall.push([5.5074691772461E-5, 8]);
   ComplexSignalInterruptionBeforeInstall.push([0.00011110305786133, 16]);
   ComplexSignalInterruptionBeforeInstall.push([0.00018906593322754, 32]);
   ComplexSignalInterruptionBeforeInstall.push([0.00037503242492676, 64]);
   ComplexSignalInterruptionBeforeInstall.push([0.00076699256896973, 128]);
   ComplexSignalInterruptionBeforeInstall.push([0.0014290809631348, 256]);
   ComplexSignalInterruptionBeforeInstall.push([0.002932071685791, 512]);
   ComplexSignalInterruptionBeforeInstall.push([0.0058200359344482, 1024]);
   ComplexSignalInterruptionBeforeInstall.push([0.012225151062012, 2048]);
   ComplexSignalInterruptionBeforeInstall.push([0.028653860092163, 4096]);
   ComplexSignalInterruptionBeforeInstall.push([0.074035882949829, 8192]);
   var ComplexSignalInterruptionBeforeInstall_graph = google.visualization.arrayToDataTable(ComplexSignalInterruptionBeforeInstall);
   var ComplexSignalInterruptionBeforeInstall_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalInterruptionBeforeInstall"));
   ComplexSignalInterruptionBeforeInstall_chart.draw(ComplexSignalInterruptionBeforeInstall_graph, {title: "Complex Signal Interruption Before Install"});
   var ComplexSignalInterruptionAfterInstall = [["Time", "Complex Signal Interruption After Install"]];
   ComplexSignalInterruptionAfterInstall.push([3.504753112793E-5, 2]);
   ComplexSignalInterruptionAfterInstall.push([2.5033950805664E-5, 4]);
   ComplexSignalInterruptionAfterInstall.push([5.8174133300781E-5, 8]);
   ComplexSignalInterruptionAfterInstall.push([0.00012493133544922, 16]);
   ComplexSignalInterruptionAfterInstall.push([0.00021910667419434, 32]);
   ComplexSignalInterruptionAfterInstall.push([0.00047421455383301, 64]);
   ComplexSignalInterruptionAfterInstall.push([0.00090503692626953, 128]);
   ComplexSignalInterruptionAfterInstall.push([0.0016200542449951, 256]);
   ComplexSignalInterruptionAfterInstall.push([0.0028641223907471, 512]);
   ComplexSignalInterruptionAfterInstall.push([0.0058071613311768, 1024]);
   ComplexSignalInterruptionAfterInstall.push([0.012244939804077, 2048]);
   ComplexSignalInterruptionAfterInstall.push([0.029853105545044, 4096]);
   ComplexSignalInterruptionAfterInstall.push([0.077439069747925, 8192]);
   var ComplexSignalInterruptionAfterInstall_graph = google.visualization.arrayToDataTable(ComplexSignalInterruptionAfterInstall);
   var ComplexSignalInterruptionAfterInstall_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalInterruptionAfterInstall"));
   ComplexSignalInterruptionAfterInstall_chart.draw(ComplexSignalInterruptionAfterInstall_graph, {title: "Complex Signal Interruption After Install"});
   var ComplexSignalInterruptionBefore = [["Time", "Complex Signal Interruption Before"]];
   ComplexSignalInterruptionBefore.push([8.082389831543E-5, 2]);
   ComplexSignalInterruptionBefore.push([5.7220458984375E-5, 4]);
   ComplexSignalInterruptionBefore.push([0.00011205673217773, 8]);
   ComplexSignalInterruptionBefore.push([0.00027990341186523, 16]);
   ComplexSignalInterruptionBefore.push([0.00041389465332031, 32]);
   ComplexSignalInterruptionBefore.push([0.00070500373840332, 64]);
   ComplexSignalInterruptionBefore.push([0.0014278888702393, 128]);
   ComplexSignalInterruptionBefore.push([0.0026628971099854, 256]);
   ComplexSignalInterruptionBefore.push([0.0061190128326416, 512]);
   ComplexSignalInterruptionBefore.push([0.012849092483521, 1024]);
   ComplexSignalInterruptionBefore.push([0.024565935134888, 2048]);
   ComplexSignalInterruptionBefore.push([0.052355051040649, 4096]);
   ComplexSignalInterruptionBefore.push([0.11630606651306, 8192]);
   var ComplexSignalInterruptionBefore_graph = google.visualization.arrayToDataTable(ComplexSignalInterruptionBefore);
   var ComplexSignalInterruptionBefore_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalInterruptionBefore"));
   ComplexSignalInterruptionBefore_chart.draw(ComplexSignalInterruptionBefore_graph, {title: "Complex Signal Interruption Before"});
   var ComplexSignalInterruptionAfter = [["Time", "Complex Signal Interruption After"]];
   ComplexSignalInterruptionAfter.push([5.7220458984375E-5, 2]);
   ComplexSignalInterruptionAfter.push([5.1975250244141E-5, 4]);
   ComplexSignalInterruptionAfter.push([0.00010991096496582, 8]);
   ComplexSignalInterruptionAfter.push([0.00022315979003906, 16]);
   ComplexSignalInterruptionAfter.push([0.00040006637573242, 32]);
   ComplexSignalInterruptionAfter.push([0.00073790550231934, 64]);
   ComplexSignalInterruptionAfter.push([0.0014879703521729, 128]);
   ComplexSignalInterruptionAfter.push([0.0033957958221436, 256]);
   ComplexSignalInterruptionAfter.push([0.0065479278564453, 512]);
   ComplexSignalInterruptionAfter.push([0.013594150543213, 1024]);
   ComplexSignalInterruptionAfter.push([0.026800155639648, 2048]);
   ComplexSignalInterruptionAfter.push([0.058552980422974, 4096]);
   ComplexSignalInterruptionAfter.push([0.11541485786438, 8192]);
   var ComplexSignalInterruptionAfter_graph = google.visualization.arrayToDataTable(ComplexSignalInterruptionAfter);
   var ComplexSignalInterruptionAfter_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalInterruptionAfter"));
   ComplexSignalInterruptionAfter_chart.draw(ComplexSignalInterruptionAfter_graph, {title: "Complex Signal Interruption After"});
       }
    </script>
    <div id="ProcessesInstalled" style="width: 450px; height: 250px; float:left;"></div><div id="SignalsEmitted" style="width: 450px; height: 250px; float:left;"></div><div id="SignalRegistration" style="width: 450px; height: 250px; float:left;"></div><div id="ListnersInstalled" style="width: 450px; height: 250px; float:left;"></div><div id="InterruptionsInstalled" style="width: 450px; height: 250px; float:left;"></div><div id="LoopsPerformed" style="width: 450px; height: 250px; float:left;"></div><div id="Interruptionbeforeemit" style="width: 450px; height: 250px; float:left;"></div><div id="Interruptionafteremit" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalRegistration" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalEvaluation" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalInterruptionBeforeInstall" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalInterruptionAfterInstall" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalInterruptionBefore" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalInterruptionAfter" style="width: 450px; height: 250px; float:left;"></div>
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

