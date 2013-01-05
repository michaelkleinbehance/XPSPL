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
            ProcessesInstalled.push([0.00015902519226074, 2]);
            ProcessesInstalled.push([0.00016689300537109, 4]);
            ProcessesInstalled.push([0.00025105476379395, 8]);
            ProcessesInstalled.push([0.00031900405883789, 16]);
            ProcessesInstalled.push([0.00061202049255371, 32]);
            ProcessesInstalled.push([0.0011959075927734, 64]);
            ProcessesInstalled.push([0.0024068355560303, 128]);
            ProcessesInstalled.push([0.0048470497131348, 256]);
            ProcessesInstalled.push([0.009627103805542, 512]);
            ProcessesInstalled.push([0.019468069076538, 1024]);
            ProcessesInstalled.push([0.039439916610718, 2048]);
            ProcessesInstalled.push([0.095212936401367, 4096]);
            var ProcessesInstalled_graph = google.visualization.arrayToDataTable(ProcessesInstalled);
            var ProcessesInstalled_chart = new google.visualization.LineChart(document.getElementById("ProcessesInstalled"));
            ProcessesInstalled_chart.draw(ProcessesInstalled_graph, {title: "Processes Installed"});
            var SignalsEmitted = [["Time", "Signals Emitted"]];
            SignalsEmitted.push([2.4080276489258E-5, 2]);
            SignalsEmitted.push([3.504753112793E-5, 4]);
            SignalsEmitted.push([0.00018191337585449, 8]);
            SignalsEmitted.push([0.00012588500976562, 16]);
            SignalsEmitted.push([0.0002439022064209, 32]);
            SignalsEmitted.push([0.00045108795166016, 64]);
            SignalsEmitted.push([0.00098013877868652, 128]);
            SignalsEmitted.push([0.00193190574646, 256]);
            SignalsEmitted.push([0.0040740966796875, 512]);
            SignalsEmitted.push([0.0071060657501221, 1024]);
            SignalsEmitted.push([0.014472007751465, 2048]);
            SignalsEmitted.push([0.031413078308105, 4096]);
            var SignalsEmitted_graph = google.visualization.arrayToDataTable(SignalsEmitted);
            var SignalsEmitted_chart = new google.visualization.LineChart(document.getElementById("SignalsEmitted"));
            SignalsEmitted_chart.draw(SignalsEmitted_graph, {title: "Signals Emitted"});
            var SignalRegistration = [["Time", "Signal Registration"]];
            SignalRegistration.push([5.6982040405273E-5, 2]);
            SignalRegistration.push([4.1961669921875E-5, 4]);
            SignalRegistration.push([7.1048736572266E-5, 8]);
            SignalRegistration.push([0.00014281272888184, 16]);
            SignalRegistration.push([0.00025606155395508, 32]);
            SignalRegistration.push([0.00050616264343262, 64]);
            SignalRegistration.push([0.00099086761474609, 128]);
            SignalRegistration.push([0.0020191669464111, 256]);
            SignalRegistration.push([0.0040268898010254, 512]);
            SignalRegistration.push([0.0080940723419189, 1024]);
            SignalRegistration.push([0.016294002532959, 2048]);
            SignalRegistration.push([0.033431053161621, 4096]);
            var SignalRegistration_graph = google.visualization.arrayToDataTable(SignalRegistration);
            var SignalRegistration_chart = new google.visualization.LineChart(document.getElementById("SignalRegistration"));
            SignalRegistration_chart.draw(SignalRegistration_graph, {title: "Signal Registration"});
            var ListnersInstalled = [["Time", "Listners Installed"]];
            ListnersInstalled.push([0.00017595291137695, 2]);
            ListnersInstalled.push([0.00015497207641602, 4]);
            ListnersInstalled.push([0.00027108192443848, 8]);
            ListnersInstalled.push([0.00055885314941406, 16]);
            ListnersInstalled.push([0.0010619163513184, 32]);
            ListnersInstalled.push([0.0019538402557373, 64]);
            ListnersInstalled.push([0.0038051605224609, 128]);
            ListnersInstalled.push([0.0077180862426758, 256]);
            ListnersInstalled.push([0.015593767166138, 512]);
            ListnersInstalled.push([0.031619071960449, 1024]);
            ListnersInstalled.push([0.063469886779785, 2048]);
            ListnersInstalled.push([0.14666295051575, 4096]);
            var ListnersInstalled_graph = google.visualization.arrayToDataTable(ListnersInstalled);
            var ListnersInstalled_chart = new google.visualization.LineChart(document.getElementById("ListnersInstalled"));
            ListnersInstalled_chart.draw(ListnersInstalled_graph, {title: "Listners Installed"});
            var InterruptionsInstalled = [["Time", "Interruptions Installed"]];
            InterruptionsInstalled.push([0.00010418891906738, 2]);
            InterruptionsInstalled.push([6.4849853515625E-5, 4]);
            InterruptionsInstalled.push([0.00013399124145508, 8]);
            InterruptionsInstalled.push([0.00022411346435547, 16]);
            InterruptionsInstalled.push([0.00043797492980957, 32]);
            InterruptionsInstalled.push([0.00085592269897461, 64]);
            InterruptionsInstalled.push([0.0016698837280273, 128]);
            InterruptionsInstalled.push([0.0032989978790283, 256]);
            InterruptionsInstalled.push([0.006695032119751, 512]);
            InterruptionsInstalled.push([0.01335597038269, 1024]);
            InterruptionsInstalled.push([0.027154922485352, 2048]);
            InterruptionsInstalled.push([0.064295053482056, 4096]);
            var InterruptionsInstalled_graph = google.visualization.arrayToDataTable(InterruptionsInstalled);
            var InterruptionsInstalled_chart = new google.visualization.LineChart(document.getElementById("InterruptionsInstalled"));
            InterruptionsInstalled_chart.draw(InterruptionsInstalled_graph, {title: "Interruptions Installed"});
            var LoopsPerformed = [["Time", "Loops Performed"]];
            LoopsPerformed.push([6.6995620727539E-5, 2]);
            LoopsPerformed.push([0.00010204315185547, 4]);
            LoopsPerformed.push([0.0001978874206543, 8]);
            LoopsPerformed.push([0.00041913986206055, 16]);
            LoopsPerformed.push([0.00087523460388184, 32]);
            LoopsPerformed.push([0.0016028881072998, 64]);
            LoopsPerformed.push([0.003201961517334, 128]);
            LoopsPerformed.push([0.0063240528106689, 256]);
            LoopsPerformed.push([0.012619018554688, 512]);
            LoopsPerformed.push([0.033747911453247, 1024]);
            LoopsPerformed.push([0.051707983016968, 2048]);
            LoopsPerformed.push([0.099022150039673, 4096]);
            var LoopsPerformed_graph = google.visualization.arrayToDataTable(LoopsPerformed);
            var LoopsPerformed_chart = new google.visualization.LineChart(document.getElementById("LoopsPerformed"));
            LoopsPerformed_chart.draw(LoopsPerformed_graph, {title: "Loops Performed"});
            var Interruptionbeforeemit = [["Time", "Interruption before emit"]];
            Interruptionbeforeemit.push([0.00013399124145508, 2]);
            Interruptionbeforeemit.push([9.7990036010742E-5, 4]);
            Interruptionbeforeemit.push([0.00019693374633789, 8]);
            Interruptionbeforeemit.push([0.00035190582275391, 16]);
            Interruptionbeforeemit.push([0.00070500373840332, 32]);
            Interruptionbeforeemit.push([0.0013940334320068, 64]);
            Interruptionbeforeemit.push([0.0027570724487305, 128]);
            Interruptionbeforeemit.push([0.0055298805236816, 256]);
            Interruptionbeforeemit.push([0.011093139648438, 512]);
            Interruptionbeforeemit.push([0.023092031478882, 1024]);
            Interruptionbeforeemit.push([0.044953107833862, 2048]);
            Interruptionbeforeemit.push([0.10593605041504, 4096]);
            var Interruptionbeforeemit_graph = google.visualization.arrayToDataTable(Interruptionbeforeemit);
            var Interruptionbeforeemit_chart = new google.visualization.LineChart(document.getElementById("Interruptionbeforeemit"));
            Interruptionbeforeemit_chart.draw(Interruptionbeforeemit_graph, {title: "Interruption before emit"});
            var Interruptionafteremit = [["Time", "Interruption after emit"]];
            Interruptionafteremit.push([0.00014090538024902, 2]);
            Interruptionafteremit.push([0.00014400482177734, 4]);
            Interruptionafteremit.push([0.00026392936706543, 8]);
            Interruptionafteremit.push([0.0003659725189209, 16]);
            Interruptionafteremit.push([0.00069880485534668, 32]);
            Interruptionafteremit.push([0.0013852119445801, 64]);
            Interruptionafteremit.push([0.0027849674224854, 128]);
            Interruptionafteremit.push([0.0054831504821777, 256]);
            Interruptionafteremit.push([0.011265993118286, 512]);
            Interruptionafteremit.push([0.022682905197144, 1024]);
            Interruptionafteremit.push([0.044682025909424, 2048]);
            Interruptionafteremit.push([0.10351395606995, 4096]);
            var Interruptionafteremit_graph = google.visualization.arrayToDataTable(Interruptionafteremit);
            var Interruptionafteremit_chart = new google.visualization.LineChart(document.getElementById("Interruptionafteremit"));
            Interruptionafteremit_chart.draw(Interruptionafteremit_graph, {title: "Interruption after emit"});
            var ComplexSignalRegistration = [["Time", "Complex Signal Registration"]];
            ComplexSignalRegistration.push([6.2227249145508E-5, 2]);
            ComplexSignalRegistration.push([4.5061111450195E-5, 4]);
            ComplexSignalRegistration.push([8.2015991210938E-5, 8]);
            ComplexSignalRegistration.push([0.00014996528625488, 16]);
            ComplexSignalRegistration.push([0.00029110908508301, 32]);
            ComplexSignalRegistration.push([0.00053191184997559, 64]);
            ComplexSignalRegistration.push([0.0010650157928467, 128]);
            ComplexSignalRegistration.push([0.0021259784698486, 256]);
            ComplexSignalRegistration.push([0.0042259693145752, 512]);
            ComplexSignalRegistration.push([0.0090591907501221, 1024]);
            ComplexSignalRegistration.push([0.017676115036011, 2048]);
            ComplexSignalRegistration.push([0.04702091217041, 4096]);
            var ComplexSignalRegistration_graph = google.visualization.arrayToDataTable(ComplexSignalRegistration);
            var ComplexSignalRegistration_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalRegistration"));
            ComplexSignalRegistration_chart.draw(ComplexSignalRegistration_graph, {title: "Complex Signal Registration"});
            var ComplexSignalEvaluation = [["Time", "Complex Signal Evaluation"]];
            ComplexSignalEvaluation.push([1.8119812011719E-5, 2]);
            ComplexSignalEvaluation.push([2.598762512207E-5, 4]);
            ComplexSignalEvaluation.push([4.9829483032227E-5, 8]);
            ComplexSignalEvaluation.push([9.2983245849609E-5, 16]);
            ComplexSignalEvaluation.push([0.00019192695617676, 32]);
            ComplexSignalEvaluation.push([0.00038290023803711, 64]);
            ComplexSignalEvaluation.push([0.00073099136352539, 128]);
            ComplexSignalEvaluation.push([0.0014669895172119, 256]);
            ComplexSignalEvaluation.push([0.0029640197753906, 512]);
            ComplexSignalEvaluation.push([0.0058720111846924, 1024]);
            ComplexSignalEvaluation.push([0.012281894683838, 2048]);
            ComplexSignalEvaluation.push([0.024410009384155, 4096]);
            var ComplexSignalEvaluation_graph = google.visualization.arrayToDataTable(ComplexSignalEvaluation);
            var ComplexSignalEvaluation_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalEvaluation"));
            ComplexSignalEvaluation_chart.draw(ComplexSignalEvaluation_graph, {title: "Complex Signal Evaluation"});
            var ComplexSignalInterruptionBeforeInstall = [["Time", "Complex Signal Interruption Before Install"]];
            ComplexSignalInterruptionBeforeInstall.push([0.00010108947753906, 2]);
            ComplexSignalInterruptionBeforeInstall.push([6.5088272094727E-5, 4]);
            ComplexSignalInterruptionBeforeInstall.push([0.00012111663818359, 8]);
            ComplexSignalInterruptionBeforeInstall.push([0.00022101402282715, 16]);
            ComplexSignalInterruptionBeforeInstall.push([0.00041508674621582, 32]);
            ComplexSignalInterruptionBeforeInstall.push([0.00081801414489746, 64]);
            ComplexSignalInterruptionBeforeInstall.push([0.0016059875488281, 128]);
            ComplexSignalInterruptionBeforeInstall.push([0.0031559467315674, 256]);
            ComplexSignalInterruptionBeforeInstall.push([0.0065789222717285, 512]);
            ComplexSignalInterruptionBeforeInstall.push([0.012967109680176, 1024]);
            ComplexSignalInterruptionBeforeInstall.push([0.026712894439697, 2048]);
            ComplexSignalInterruptionBeforeInstall.push([0.0626060962677, 4096]);
            var ComplexSignalInterruptionBeforeInstall_graph = google.visualization.arrayToDataTable(ComplexSignalInterruptionBeforeInstall);
            var ComplexSignalInterruptionBeforeInstall_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalInterruptionBeforeInstall"));
            ComplexSignalInterruptionBeforeInstall_chart.draw(ComplexSignalInterruptionBeforeInstall_graph, {title: "Complex Signal Interruption Before Install"});
            var ComplexSignalInterruptionAfterInstall = [["Time", "Complex Signal Interruption After Install"]];
            ComplexSignalInterruptionAfterInstall.push([0.00010800361633301, 2]);
            ComplexSignalInterruptionAfterInstall.push([6.5088272094727E-5, 4]);
            ComplexSignalInterruptionAfterInstall.push([0.00012302398681641, 8]);
            ComplexSignalInterruptionAfterInstall.push([0.0002448558807373, 16]);
            ComplexSignalInterruptionAfterInstall.push([0.00043106079101562, 32]);
            ComplexSignalInterruptionAfterInstall.push([0.0008549690246582, 64]);
            ComplexSignalInterruptionAfterInstall.push([0.0017120838165283, 128]);
            ComplexSignalInterruptionAfterInstall.push([0.0036420822143555, 256]);
            ComplexSignalInterruptionAfterInstall.push([0.0068149566650391, 512]);
            ComplexSignalInterruptionAfterInstall.push([0.013913154602051, 1024]);
            ComplexSignalInterruptionAfterInstall.push([0.028582096099854, 2048]);
            ComplexSignalInterruptionAfterInstall.push([0.069118976593018, 4096]);
            var ComplexSignalInterruptionAfterInstall_graph = google.visualization.arrayToDataTable(ComplexSignalInterruptionAfterInstall);
            var ComplexSignalInterruptionAfterInstall_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalInterruptionAfterInstall"));
            ComplexSignalInterruptionAfterInstall_chart.draw(ComplexSignalInterruptionAfterInstall_graph, {title: "Complex Signal Interruption After Install"});
            var ComplexSignalInterruptionBefore = [["Time", "Complex Signal Interruption Before"]];
            ComplexSignalInterruptionBefore.push([0.00014805793762207, 2]);
            ComplexSignalInterruptionBefore.push([0.0001060962677002, 4]);
            ComplexSignalInterruptionBefore.push([0.0001981258392334, 8]);
            ComplexSignalInterruptionBefore.push([0.00041103363037109, 16]);
            ComplexSignalInterruptionBefore.push([0.00076413154602051, 32]);
            ComplexSignalInterruptionBefore.push([0.0015239715576172, 64]);
            ComplexSignalInterruptionBefore.push([0.0030269622802734, 128]);
            ComplexSignalInterruptionBefore.push([0.0062758922576904, 256]);
            ComplexSignalInterruptionBefore.push([0.013376951217651, 512]);
            ComplexSignalInterruptionBefore.push([0.02681303024292, 1024]);
            ComplexSignalInterruptionBefore.push([0.055827140808105, 2048]);
            ComplexSignalInterruptionBefore.push([0.12030696868896, 4096]);
            var ComplexSignalInterruptionBefore_graph = google.visualization.arrayToDataTable(ComplexSignalInterruptionBefore);
            var ComplexSignalInterruptionBefore_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalInterruptionBefore"));
            ComplexSignalInterruptionBefore_chart.draw(ComplexSignalInterruptionBefore_graph, {title: "Complex Signal Interruption Before"});
            var ComplexSignalInterruptionAfter = [["Time", "Complex Signal Interruption After"]];
            ComplexSignalInterruptionAfter.push([0.00014495849609375, 2]);
            ComplexSignalInterruptionAfter.push([0.00010991096496582, 4]);
            ComplexSignalInterruptionAfter.push([0.00020718574523926, 8]);
            ComplexSignalInterruptionAfter.push([0.00039482116699219, 16]);
            ComplexSignalInterruptionAfter.push([0.00079512596130371, 32]);
            ComplexSignalInterruptionAfter.push([0.0015220642089844, 64]);
            ComplexSignalInterruptionAfter.push([0.0030648708343506, 128]);
            ComplexSignalInterruptionAfter.push([0.0060470104217529, 256]);
            ComplexSignalInterruptionAfter.push([0.011991024017334, 512]);
            ComplexSignalInterruptionAfter.push([0.026979207992554, 1024]);
            ComplexSignalInterruptionAfter.push([0.049118995666504, 2048]);
            ComplexSignalInterruptionAfter.push([0.11069321632385, 4096]);
            var ComplexSignalInterruptionAfter_graph = google.visualization.arrayToDataTable(ComplexSignalInterruptionAfter);
            var ComplexSignalInterruptionAfter_chart = new google.visualization.LineChart(document.getElementById("ComplexSignalInterruptionAfter"));
            ComplexSignalInterruptionAfter_chart.draw(ComplexSignalInterruptionAfter_graph, {title: "Complex Signal Interruption After"});
                }
    </script>
    <div id="ProcessesInstalled" style="width: 450px; height: 250px; float:left;"></div><div id="SignalsEmitted" style="width: 450px; height: 250px; float:left;"></div><div id="SignalRegistration" style="width: 450px; height: 250px; float:left;"></div><div id="ListnersInstalled" style="width: 450px; height: 250px; float:left;"></div><div id="InterruptionsInstalled" style="width: 450px; height: 250px; float:left;"></div><div id="LoopsPerformed" style="width: 450px; height: 250px; float:left;"></div><div id="Interruptionbeforeemit" style="width: 450px; height: 250px; float:left;"></div><div id="Interruptionafteremit" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalRegistration" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalEvaluation" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalInterruptionBeforeInstall" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalInterruptionAfterInstall" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalInterruptionBefore" style="width: 450px; height: 250px; float:left;"></div><div id="ComplexSignalInterruptionAfter" style="width: 450px; height: 250px; float:left;"></div><div style="clear: both;"></div>
   
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

