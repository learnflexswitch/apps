<?php
/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2017-2017, Whistler - http://www.istuary.com
 *
 * @package    observium
 * @subpackage webui
 * @author     York Chen <york.chen@istuary.com>
 * @copyright  (C) 2017-2017 Whistle
 *
 */

if ($_SESSION['userlevel'] < 10)
{
  print_error_permission();
  return;
}

include($config['html_dir']."/pages/sw-frontend-dpi-menu.inc.php");
register_html_title("All Rules");

$userlist = array();
?>
 
<link href="css/sw-frontend-dpi-rule-new.css" rel="stylesheet" type="text/css" />           


          <div class="dpibody" ng-app="swFrontendDpiRuleNew" ng-controller="swFrontendDpiRuleNewCtrl" >
              <input ng-model="isInputReady" type='hidden' ng-show="false" ></input>
            <!-- This is the rule header section -->
            <div class="rulebody mainsquares" >
                <!-- This is the top line in the header starting with action -->
                <div id="headerInner1">
                    <select  ng-model="categoryForm" name="categoryForm" id="categoryForm">
                      <option ng-repeat="option in categories" value="{{option.id}}">{{option.title}}</option>
                    </select>
                    
                    <select ng-model="actionForm" name="actionForm" id="actionForm" >
                      <option ng-repeat="option in actions" value="{{option.id}}">{{option.name}}</option>
                    </select>
                    
                    <select ng-model="protoForm" name="protoForm" id="protoForm">
                      <option ng-repeat="option in protocols" value="{{option.id}}">{{option.name}}</option>
                    </select>
                    
                    <input class="headerelement" ng-model="srcip" id="srcip" placeholder="source ip"></input>
                    <input class="headerelement portelement" ng-model="srcport" id="srcport" placeholder="source port"></input>
                    &nbsp<strong id="rightarrow">&#xbb;</strong>&nbsp
                    <input class="headerelement" ng-model="dstip" id="dstip"  placeholder="dest ip"></input>


                    <input class="headerelement portelement" ng-model="dstport" id="dstport"  placeholder="dest port"></input>

                    <span ng-hide="false" class="headerelement3"><small >&nbsp sid: &nbsp; {{sid}} &nbsp; &nbsp;rev: {{rev}}</small></span>
                                 
                    <input class="headerelement dpiIdElement" ng-model="sid" id="sid" value="{{sid}}" type='hidden' ng-readonly="true" ></input>
                    <input class="headerelement dpiRevElement" ng-model="rev" id="rev" value="{{rev}}" type='hidden' ng-readonly="true" ></input>
                    
                    
                </div>
                <!-- Second line -->
                <div id="headerInner2">
                    <input  class="headerelement" ng-model="headermessage" id="headermessage" placeholder="Rule Message ( \ Escape special characters)"></input>
                    <input  class="headerelement" id="classtype" placeholder="Class-Type"></input>
                    <select  class="headerelement" id="priority">
                      <option value="">Priority</option>                      
                      <option ng-repeat="option in priorities" value="priority:{{option}};" >{{option}}</option>
                    </select>
                    <input  class="headerelement" id="gid" placeholder="gid"></input>
                </div>
            </div>

            <!-- This is the LEFT Main box holind protocol option box -->
            <div class="protoOptions mainsquares">
                <div id="ip" class="selectedProtoOptions">
                    </br></br></br><h2>IP</h2>
                    </br></br></br>
                    <select class="ttlevaluator tcpinputs" id="ttlevaluator" >
                      <option value="&gt;">&gt;</option>
                      <option value="&lt;">&lt;</option>
                      <option value="=">=</option>
                      <option selected="selected" value="">TTL</option>
                    </select>
                    
                    <input class="ttlfield tcpinputs" id="ttl" type="text" />
                    </br></br></br>
                    <select class="ipprotoevaluator tcpinputs" id="ipprotoevaluator" size="1">
                      <option value="&gt;">&gt;</option>
                      <option value="&lt;">&lt;</option>
                      <option value="=">=</option>
                      <option selected="selected" value="">IP PROTOCOL</option>
                    </select>
                    <input class="ipprotofield tcpinputs" id="ipprotofield" type="text" />
                </div>
                <!-- TCP Options -->
                <div id="tcp" class="selectedProtoOptions">
                    <h2>TCP</h2>
                    <select class="tcpinputs" ng-model="httpmethodForm" id="httpmethodForm" >
                      <option ng-repeat="option in httpmethods" value='content:"{{option}}"; http_method;'>{{option}}</option>
                      <option selected="selected" value="">HTTP REQUEST METHOD</option>
                      </select>&nbsp
                      <select  ng-model="httpstatuscode" class="tcpinputs" style="border-radius:5px; background-color:#f2f2f2; padding:3px;" id="httpstatuscode">
                        <option ng-repeat="option in httpstatuscodes" value="{{option}}">{{option}}</option>                        
                        <option selected="selected" value="">HTTP RESPONSE CODE</option>                        
                      </select></br>
                    </br></br>
                    ACK<input style="border-radius:5px; background-color:#f2f2f2; padding:3px;" id="ACK" class=" check2 opflags opflags" type="checkbox" value="A" />&nbsp;
                    SYN<input id="SYN"  class=" check2 opflags" type="checkbox" value="S" />&nbsp;
                    PSH<input id="PSH"  class=" check2 opflags" type="checkbox" value="P" />&nbsp;
                    RST<input id="RST"  class=" check2 opflags" type="checkbox" value="R" />&nbsp;
                    FIN<input id="FIN"  class=" check2 opflags" type="checkbox" value="F" />&nbsp;
                    URG<input id="URG"  class=" check2 opflags" type="checkbox" value="U" />&nbsp;
                    +<input id="flagplus" class=" check2 opflags flagoptions" type="checkbox" value="+" />&nbsp;
                    *<input id="wildcard" class=" check2 opflags flagoptions" type="checkbox" value="*" /></br>
                    </br></br>
                    <select class="tcpinputs" id="tcpdirectionForm">
                      <option ng-repeat="option in tcpdirections"  value="{{option}}">{{option}}</option>
                      <option selected="selected" value="">DIRECTION</option>
                    </select>&nbsp
                    <select  class="tcpinputs" id="tcpstateForm">                      
                      <option ng-repeat="option in tcpstatus" value="{{option}}">{{option}}</option>
                      <option selected="selected" value="">TCP STATE</option>
                    </select>
                </div>
                <!-- UDP Options -->
                <div id="udp" class="selectedProtoOptions">
                    </br></br></br><h2>UDP</h2>
                    </br></br></br><select style="width: 90%;" id="udpdirectionForm"><option value="FROM_SERVER">FROM_SERVER</option><option value="TO_SERVER">TO_SERVER</option><option value="TO_CLIENT">TO_CLIENT</option><option value="FROM_CLIENT">FROM_CLIENT</option><option selected="selected" value="">DIRECTION</option></select>
                </div>
                <!-- ICMP Options -->
                <div id="icmp" class="selectedProtoOptions">
                    </br></br></br><h2>ICMP</h2>
                    <select  class="tcpinputs" id="icmptypeevaluator"><option value="&gt;">&gt;</option><option value="&lt;">&lt;</option><option value="=">=</option><option selected="selected" value="">ICMP TYPE</option></select><input  class="tcpinputs" id="icmptype" type="text" /></br>
                    </br></br></br><select  class="tcpinputs" id="icmpcodeevaluator"><option value="&gt;">&gt;</option><option value="&lt;">&lt;</option><option value="=">=</option><option selected="selected" value="">ICMP CODE</option></select><input  class="tcpinputs" id="icmpcode" type="text" />
                </div>
                <!-- Horozontal Line -->
                <hr class="style1">
                <!-- This holds the threshold tracking and reference input -->
                <div id="miscOptions">
                    <select id="datasizeEval" style="width: 30%;">
                      <option value="&gt;">&gt;</option>
                      <option value="&lt;">&lt;</option>
                      <option value="=">=</option>
                      <option selected="selected" value="">Data Size</option>
                      </select>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                      <input style="width: 50%;" id="datasize"type="text" /></p>
                    <select id="reftype" style="width: 30%;">
                    
                      <option ng-repeat="option in reftypes" value="{{option}}">{{option}}</option>
                      <option selected="selected" value="">Reference</option>
                      </select>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input  style="width: 50%;" id="referencetext" size="40" type="text" /><div style="position: relative; height: 20px; width: 20px;"></div>
                    <select style="width: 180px" id="thresholdtype">
                      <option value="limit">limit</option>
                      <option value="threshold">threshold</option>
                      <option value="both">both</option>
                      <option selected="selected" value="">Threshold Tracking Type</option>
                      </select>&nbsp
                      
                      <select style="width: 80px" id="trackby">
                        <option value="by_src">by_src</option>
                        <option value="by_dst">by_dst</option>
                        <option selected="selected" value="">TRK BY</option>
                        </select>&nbsp
                        
                      <input style="width: 70px" id="count" placeholder="Count #"/>&nbsp
                      <input style="width: 70px" id="seconds" placeholder="Seconds"/>
                      
                      <div style="position: relative; height: 20px; width: 20px;"></div>
                </div>
            </div>
            
            <!-- This holds the content and Regex matching -->
            <div class="contentMatch mainsquares">
                <!-- The content plus image -->
                <div id="contentplus" class="item contentpluses">
                    <p class="contentHeaders">Add Content Match</p>
                    <img  class="plusexplode" id="contentArrow1" src="images/sw-frontend-dpi-rule-new-green_plus.png" width="30" height="30">
                    <div class="item-overlay top"></div>
                </div>
                <!-- This appears when you click the green plus image -->
                <div id="contentdiv">
                        <input id="thecontent" placeholder="Content Match"></input></br>
                        <input class="diditinput" id="theoffset" placeholder="Offset"></input>
                        <input class="diditinput" id="thedepth" placeholder="Depth"></input>
                        <text class="check" style="top: 78px; left: 59%;">nocase</text><input id="content1nocase" class="check" style="left: 70%;" type="checkbox" value="nocase" />
                        <text class="check" style="top: 78px; left: 76%;">uri</text><input id="content1uri" class="check" style="left: 80.3%;" type="checkbox" value="uri" />
                        <text class="check" style="top: 78px; left: 86.5%;">not</text><input id="content1not" class="check" style="left: 91.5%;" type="checkbox" value="not" />
 
                    <div style="left: 23px; top: 75%;" id="contentcheck" class="item contentpluses">
                        <img id="" src="images/sw-frontend-dpi-rule-new-accept.png" width="25" height="25">
                    </div>
                    <div style="left: 55px; top: 76%;" id="contentundo" class="item contentpluses">
                        <img style="" id="contentundo2" src="images/sw-frontend-dpi-rule-new-undo.png" width="26" height="26">
                    </div>
                    <div style="left: 84px; top: 79%;" id="contentcancel" class="item contentpluses">
                        <img style="" id="contentcancel" src="images/sw-frontend-dpi-rule-new-cancel.png" width="20" height="20">
                    </div>

                                      
                </div>
                <!-- Horozontal line -->
                <hr class="style1">
                <!-- Regex gree plus symbol -->
                <div  id="preplus" style="top: 245px;"  class="item contentpluses">
                    <p class="contentHeaders">Add Regex Match</p>
                    <img class="plusexplode" id="contentArrow2" src="images/sw-frontend-dpi-rule-new-green_plus.png"  width="30" height="30">
                    <div class="item-overlay top"></div>
                </div>
                <!-- The regex panel appears when green plus symbol is clicked -->
                <div id="pcrediv">
                        <input id="theregex" placeholder="Regular Expression"></input></br>
                        <text class="check" style="top: 78px; left: 5%;">dotal /s</text>
                          <input id="redotal" class="check" style="left: 13.5%;" type="checkbox" value="nocase" />
                        <text class="check" style="top: 78px; left: 20%;">nocase</text><input id="renocase" class="check" style="left: 28.5%;" type="checkbox" value="uri" />
                        <text class="check" style="top: 78px; left: 35%;">greedy /G</text><input id="regreedy" class="check" style="left: 46.5%;" type="checkbox" value="not" />
                        <text class="check" style="top: 78px; left: 53%;">newline /m</text><input id="renewline" class="check" style="left: 66%;" type="checkbox" value="not" />
                        <text class="check" style="top: 78px; left: 73%;">whitespace /x</text><input id="rewhitespace" class="check" style="left: 89.5%;" type="checkbox" value="not" />
 
                    <div style="left: 23px; top: 75%;" id="pcrecheck" class="item contentpluses">
                        <img id="" src="images/sw-frontend-dpi-rule-new-accept.png" width="25" height="25">
                    </div>
                    <div style="left: 55px; top: 76%;" id="pcreundo" class="item contentpluses">
                        <img id="pcreundo2" src="images/sw-frontend-dpi-rule-new-undo.png" width="26" height="26">
                    </div>
                    <div style="left: 84px; top: 79%;" id="pcrecancel" class="item contentpluses">
                        <img id="" src="images/sw-frontend-dpi-rule-new-cancel.png" width="20" height="20">
                    </div>

                    
                </div>
                
            </div>

            <!-- Bottom Panel Where the rule displays -->
            <div class="ruleOutput mainsquares">
                <div id="innerRuleOutput" >

<!-- THIS BEGINS ALL OF THE OUTPUTS TO MAKE THE RULE -->

                    <text ng-model="opaction" id="opaction" name="opaction"></text>

                    <text ng-model="opprotocol" id="opprotocol"></text>
                    <text ng-model="opsrcip" id="opsrcip"></text>
                    <text ng-model="opsrcport" id="opsrcport"></text>
                    <text>-></text>
                    <text ng-model="opdstip" id="opdstip"></text>
                    <text ng-model="opdstport" id="opdstport"></text>

                    <text>(</text>

                    <text ng-model="opmessage" id="opmessage"></text>


                    <text ng-model="opProtocols" id="opProtocols" name="opProtocols">  
                        <text ng-model="optcp" id="optcp">
                            <text ng-model="opHttp" id="opHttp"></text>
                            <text ng-model="flagscombined" id="flagscombined"></text>                            
                            <text ng-model="optcpdirection" id="optcpdirection"></text>              
                        </text>

                        <text ng-model="opimcp" id="opimcp">
                            <text id="optype"></text>
                            <text id="opcode"></text>
                        </text>

                        
                        <text id="opudp"></text>
                        
                        <text id="opip">
                            <text id="opttl"></text>
                            <text id="opipprotocol"></text>
                        </text>
                    </text>


                    <!-- CONTENT -->
                    <text id="opcontentContainer"></text>


                    <!-- PCRE -->
                    <text id="oppcre"></text>

                    <!-- MISC -->
                    <text id="opmisc">
                        <text id="opdatasize"></text>                        
                        <text id="opreference"></text>                        
                        <text id="opthreshold"></text>
                    </text>

                    <!-- End of Header -->

                    <text id="opclasstype"></text>

                    <text id="oppriority"></text>

                    <text id="opgid"></text>

                    <text id="opsid"></text>

                    <text id="oprevnum"> </text>

                    <text>)</text>
                </div>
            </div>
            
            <div>
              <br/>

<!--
              <button class="btn btn-primary" name="submit" value="change_rule" ng-disabled="!isInputReady"  ng-click="saveRule()" >
<i class="icon-ok icon-white"></i> Save Rule</button>
-->
 <button class="btn btn-primary" name="submit" value="change_rule" ng-click="saveRule()" >
<i class="icon-ok icon-white"></i> Save Rule</button>
            </div>
          </div>    
 

<script src="js/angular-cookies.min.js"></script> 
<script src="js/sw-frontend-dpi-rule-new.js"></script> 

<?php
// EOF

