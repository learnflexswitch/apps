$("#particles-js").fadeTo(0, 0.2);
$(".contentpluses").fadeTo(0, 0.6);
$(".contentpluses").hover( function() {
    $('#' + this.id).fadeTo(100, 1);
});
$(".contentpluses").mouseout( function() {
    $('#' + this.id).fadeTo(100, 0.6);
});

$("#contentdiv").fadeOut( 0 );
$("#pcrediv").fadeOut( 0 );
$(".plusexplode").click(function(){
    if (this.id == 'contentArrow1'){
        $("#contentplus").toggle( "drop" );
        $("#contentdiv").fadeIn( 400 );
    } else {
        $("#preplus").toggle( "drop" );
        $("#pcrediv").fadeIn( 400 );
    }
});
$("#contentcancel").click(function() {
    $("#contentdiv").fadeOut( 400 );
    $("#contentplus").toggle( "drop" );
});
$("#pcrecancel").click(function() {
    $("#pcrediv").fadeOut( 400 );
    $("#preplus").toggle( "drop" );
});

$(".selectedProtoOptions").fadeOut(0);
$(".headerelement").fadeTo(0, 0.3);
$(".headerelement").prop('disabled', true);
$('body').on('change', '#protoForm', function(){
    $(".selectedProtoOptions").fadeOut(400);
    var theval = $("#protoForm").val();
    if (theval === "Protocol") {
        $("#opprotocol").text('');
        $(".headerelement").fadeTo(0, 0.3);
        $(".headerelement").prop('disabled', true);
        $("#opudp").css('display', 'none');
        $("#opip").css('display', 'none');
        $("#opimcp").css('display', 'none');
        $("#optcp").css('display', 'none');
    } else if (theval === "tcp") {
        $("#opprotocol").text('tcp ');
        $(".headerelement").fadeTo(0, 1);
        $(".headerelement").prop('disabled', false);
        $("#srcport").prop('disabled', false);
        $("#dstport").prop('disabled', false);
        $("#srcport").fadeTo(100, 1);
        $("#dstport").fadeTo(100, 1);
        $("#tcp").fadeIn(400);
        $("#opudp").css('display', 'none');
        $("#opip").css('display', 'none');
        $("#opimcp").css('display', 'none');
        $("#optcp").css('display', 'inline-block');
        $("#opsrcport").css('display', 'inline-block');
        $("#opdstport").css('display', 'inline-block');
    } else if (theval === "udp") {
        $("#opprotocol").text('udp ');
        $(".headerelement").fadeTo(0, 1);
        $(".headerelement").prop('disabled', false);
        $("#srcport").prop('disabled', false);
        $("#dstport").prop('disabled', false);
        $("#srcport").fadeTo(100, 1);
        $("#dstport").fadeTo(100, 1);
        $("#udp").fadeIn(400);
        $("#opudp").css('display', 'inline-block');
        $("#opip").css('display', 'none');
        $("#opimcp").css('display', 'none');
        $("#optcp").css('display', 'none');
        $("#opsrcport").css('display', 'inline-block');
        $("#opdstport").css('display', 'inline-block');
    } else if (theval === "icmp") {
        $("#opprotocol").text('icmp ');
        $(".headerelement").fadeTo(0, 1);
        $(".headerelement").prop('disabled', false);
        $("#srcport").prop('disabled', true);
        $("#dstport").prop('disabled', true);
        $("#srcport").fadeTo(100, 0.3);
        $("#dstport").fadeTo(100, 0.3);
        $("#icmp").fadeIn(400);
        $("#opudp").css('display', 'none');
        $("#opip").css('display', 'none');
        $("#opimcp").css('display', 'inline-block');
        $("#optcp").css('display', 'none');
        $("#opsrcport").css('display', 'inline-block');
        $("#opdstport").css('display', 'inline-block');
        $("#opsrcport").text('any');
        $("#opdstport").text('any');
    } else if (theval === "ip") {
        $("#opprotocol").text('ip ');
        $(".headerelement").fadeTo(0, 1);
        $(".headerelement").prop('disabled', false);
        $("#srcport").prop('disabled', true);
        $("#dstport").prop('disabled', true);
        $("#srcport").fadeTo(100, 0.3);
        $("#dstport").fadeTo(100, 0.3);
        $("#ip").fadeIn(400);
        $("#opudp").css('display', 'none');
        $("#opip").css('display', 'inline-block');
        $("#opimcp").css('display', 'none');
        $("#optcp").css('display', 'none');
        $("#opsrcport").css('display', 'inline-block');
        $("#opdstport").css('display', 'inline-block');
        $("#opsrcport").text('any');
        $("#opdstport").text('any');

    }
});

var expression = /((^\s*((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))\s*$)|(^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$))/g;
var expression2 = /\d\d?\d?\.\d\d?\d?\.\d\d?\d?\.\d\d?\d?\/\d\d?/g;
$('body').on('change', '#actionForm', function(){
    $("#opaction").text($("#actionForm").val() + ' ');
});

$("#srcip").focusout(function(){
    if ($("#srcip").val().match(expression) !== null  || $("#srcip").val().match(expression2) !== null || $("#srcip").val().match(/^(?:\!|\(|\$){1,2}(?:\.|\,|\w|\_|\d|\s|\!|\(|\))+\)?$/g) !== null || $("#srcip").val() === '' || $("#srcip").val() === 'any'){
        $("#opsrcip").text($("#srcip").val());
    } else {
        $("#srcip").clearQueue();
        $("#srcip").val("");
        $("#srcip").effect("shake");
    }
});
$("#dstip").focusout(function(){
    if ($("#dstip").val().match(expression) !== null  || $("#srcip").val().match(expression2) !== null || $("#dstip").val().match(/^(?:\!|\(|\$){1,2}(?:\.|\,|\w|\_|\d|\s|\!|\(|\))+\)?$/g) !== null || $("#dstip").val() === '' || $("#dstip").val() === 'any'){
        $("#opdstip").text($("#dstip").val());
    } else {
        $("#dstip").clearQueue();
        $("#dstip").val("");
        $("#dstip").effect("shake");
    }
});

$("#srcport").focusout(function(){
    if ($("#srcport").val() == "") {
        //do nothing
    } else if ($("#srcport").val() == "any") {
        $("#opsrcport").text($("#srcport").val()); 
    } else if ($("#srcport").val().match(/^(?:\$|\!|\(|\d){1}(?:\,|\$|\w|\_|\-|\d|\s|\!|\s)*\)?$/g) !== null || $("#srcport").val() === '' || $("#srcport").val() === 'any'){
        if (parseInt($("#srcport").val()) < 65535 && parseInt($("#srcport").val()) > 1) {
            $("#opsrcport").text($("#srcport").val())
        } else if ($("#srcport").val().match(/^(?:\$|\!|\(){1}(?:\$|\w)/g) !== null ) {
            $("#opsrcport").text($("#srcport").val());
        } else {
            $("#srcport").clearQueue();
            $("#srcport").val("");
            $("#srcport").effect("shake");
        }
    } else {
        $("#srcport").clearQueue();
        $("#srcport").val("");
        $("#srcport").effect("shake");
    }
});

$("#dstport").focusout(function(){
    if ($("#dstport").val() === '') {
        //do nothin
    } else if ($("#dstport").val() === 'any') {
        $("#opdstport").text($("#dstport").val());
    } else if ($("#dstport").val().match(/^(?:\$|\!|\(|\d){1}(?:\,|\$|\w|\_|\-|\d|\s|\!|\s)*\)?$/g) !== null || $("#dstport").val() === '' || $("#dstport").val() === 'any'){
        if (parseInt($("#dstport").val()) < 65535 && parseInt($("#dstport").val()) > 1) {
            $("#opdstport").text($("#dstport").val());
        } else if ($("#dstport").val().match(/^(?:\$|\!|\(){1}(?:\$|\w)/g) !== null) {
            $("#opdstport").text($("#dstport").val());
        } else {
            $("#dstport").clearQueue();
            $("#dstport").val("");
            $("#dstport").effect("shake");
        }
    } else {
        $("#dstport").clearQueue();
        $("#dstport").val("");
        $("#dstport").effect("shake");
    }
});

$("#sid").focusout(function(){
    if ($("#sid").val() ==="") {
        $("#opsid").text($("#sid").val());
    } else if (parseInt($("#sid").val()) < 1 || $("#sid").val().match(/^\d+$/g) === null) {
        $("#sid").clearQueue();
        $("#sid").val("");
        $("#sid").effect("shake");
    } else {
        $("#opsid").text(" sid:" + $("#sid").val() + ';');
    }
});

$("#rev").focusout(function(){
    if ($("#rev").val() ==="") {
        $("#oprevnum").text($("#rev").val());
    } else if (parseInt($("#rev").val()) < 1 || $("#rev").val().match(/^\d+$/g) === null) {
        $("#rev").clearQueue();
        $("#rev").val("");
        $("#rev").effect("shake");
    } else {
        $("#oprevnum").text("rev:" + $("#rev").val()+ ';');
    }
});

$("#headermessage").focusout(function(){
    $("#opsid").text(" sid:" + $("#sid").val() + ';');
    $("#oprevnum").text("rev:" + $("#rev").val()+ ';');
    if ($("#headermessage").val() ==="") {
        $("#opmessage").text($("#headermessage").val());
    } else if ($("#headermessage").val().match(/^(?:\w|\d|\.|\\\W|\s)+$/g) === null) {
        $("#headermessage").clearQueue();
        $("#headermessage").val("");
        $("#headermessage").effect("shake");
    } else {
        $("#opmessage").text("msg:\"" + $("#headermessage").val() + '";');
    }
});

$("#classtype").focusout(function(){
    if ($("#classtype").val() ==="") {
        $("#opclasstype").text($("#classtype").val());
    } else if ($("#classtype").val().match(/^\w(?:\w|\-)+\w$/g) === null) {
        $("#classtype").clearQueue();
        $("#classtype").val("");
        $("#classtype").effect("shake");
    } else {
        $("#opclasstype").text("classtype:" + $("#classtype").val() + ';');
    }
});

$("#gid").focusout(function(){
    if ($("#gid").val() ==="") {
        $("#opgid").text($("#gid").val());
    } else if ($("#gid").val().match(/^\d+$/g) === null) {
        $("#gid").clearQueue();
        $("#gid").val("");
        $("#gid").effect("shake");
    } else {
        $("#opgid").text("gid:" + $("#gid").val() + ';');
    }
});


$('body').on('change', '#priority', function(){
    $("#oppriority").text($("#priority").val());
});

$('body').on('change', '#httpmethodForm', function(){
    if ($("#httpmethodForm").val() === '') {
        $('#httpstatuscode').prop('disabled', false);
        $('#httpstatuscode').clearQueue();
        $('#httpstatuscode').fadeTo(200, 1);
        $("#opHttp").text($("#httpmethodForm").val());
    } else {
        $("#opHttp").text($("#httpmethodForm").val());
        $('#httpstatuscode').prop('disabled', true);
        $('#httpstatuscode').clearQueue();
        $('#httpstatuscode').fadeTo(200, 0.3);
    }
});

$('body').on('change', '#httpstatuscode', function(){
    if ($("#httpstatuscode").val() === '') {
        $('#httpmethodForm').prop('disabled', false);
        $('#httpmethodForm').clearQueue();
        $('#httpmethodForm').fadeTo(200, 1);
        $("#opHttp").text($("#httpmethodForm").val());
    } else {
        $("#opHttp").text("content:\""+$("#httpstatuscode").val() + "\"; http_stat_code;");
        $('#httpmethodForm').prop('disabled', true);
        $('#httpmethodForm').clearQueue();
        $('#httpmethodForm').fadeTo(200, 0.3);
    }
});

$(".opflags").click(function(){
    var theflags = '';
    if ($("#flagplus").is(':checked')) {
        theflags += '+';
    } else if ($("#wildcard").is(':checked')) {
        theflags += '*';
    }
    if ($("#ACK").is(':checked')) {
        theflags += 'A';
    }
    if ($("#SYN").is(':checked')) {
        theflags += 'S';
    }
    if ($("#PSH").is(':checked')) {
        theflags += 'P';
    }
    if ($("#RST").is(':checked')) {
        theflags += 'R';
    }
    if ($("#FIN").is(':checked')) {
        theflags += 'F';
    }
    if ($("#URG").is(':checked')) {
        theflags += 'U';
    }
    if (theflags === ""){
        $("#flagscombined").text('');
    } else {
        $("#flagscombined").text('flags:'+theflags + ';');
    }

});


$('body').on('change', '#tcpdirectionForm', function(){
    if ($("#tcpdirectionForm").val() === '') {
        $("#optcpdirection").text('');
    } else if ($("#tcpstateForm").val() === '' && $("#tcpdirectionForm").val() !== '') {
        $("#optcpdirection").text("flow:"+$("#tcpdirectionForm").val().toLowerCase()+";");
    } else if ($("#tcpdirectionForm").val() !== '') {
        $("#optcpdirection").text("flow:"+$("#tcpdirectionForm").val().toLowerCase() + ","+$("#tcpstateForm").val().toLowerCase()+";");
    }
});

$('body').on('change', '#tcpstateForm', function(){
    if ($("#tcpstateForm").val() === '' && $("#tcpdirectionForm").val() !== '') {
        $("#optcpdirection").text("flow:"+$("#tcpdirectionForm").val().toLowerCase()+";");
    } else if ($("#tcpdirectionForm").val() !== '') {
        $("#optcpdirection").text("flow:"+$("#tcpdirectionForm").val().toLowerCase() + ","+$("#tcpstateForm").val().toLowerCase()+";");
    }
});
//ICMP options icmptypeevaluator icmptype
//  icmpcodeevaluator icmpcode
$("#icmptype").focusout(function(){
    if ($("#icmptype").val() ==="") {
        $("#optype").text('');
    } else if ($("#icmptype").val().match(/^\d+$/g) !== null && $("#icmptypeevaluator").val() !== "") {
        $("#optype").text('itype:'+$("#icmptypeevaluator").val().replace('=','')+$("#icmptype").val() +';');
    } else {
        $("#icmptype").clearQueue();
        $("#icmptype").val("");
        $("#icmptype").effect("shake");
        $("#optype").text('');
    }
});

$('body').on('change', '#icmptypeevaluator', function(){
    if ($("#icmptype").val() ==="") {
        $("#optype").text('');
    } else if ($("#icmptype").val().match(/^\d+$/g) !== null && $("#icmptypeevaluator").val() !== "") {
        $("#optype").text('itype:'+$("#icmptypeevaluator").val().replace('=','')+$("#icmptype").val() +';');
    } else {
        $("#icmptype").clearQueue();
        $("#icmptype").val("");
        $("#icmptype").effect("shake");
        $("#optype").text('');
    }
});

$('body').on('change', '#icmpcodeevaluator', function(){
    if ($("#icmpcode").val() ==="") {
        $("#opcode").text('');
    } else if ($("#icmpcode").val().match(/^\d+$/g) !== null && $("#icmpcodeevaluator").val() !== "") {
        $("#opcode").text('icode:' + $("#icmpcodeevaluator").val().replace('=','') + $("#icmpcode").val() +';');
    } else {
        $("#icmpcode").clearQueue();
        $("#icmpcode").val("");
        $("#icmpcode").effect("shake");
        $("#opcode").text('');
    }
});

$("#icmpcode").focusout(function(){
    if ($("#icmpcode").val() ==="") {
        $("#opcode").text('');
    } else if ($("#icmpcode").val().match(/^\d+$/g) !== null && $("#icmpcodeevaluator").val() !== "") {
        $("#opcode").text('icode:' + $("#icmpcodeevaluator").val().replace('=','') + $("#icmpcode").val() +';');
    } else {
        $("#icmpcode").clearQueue();
        $("#icmpcode").val("");
        $("#icmpcode").effect("shake");
        $("#opcode").text('');
    }
});


$('body').on('change', '#udpdirectionForm', function(){
    if ($("#udpdirectionForm").val() === '') {
        $("#opudp").text('');
    } else {
        $("#opudp").text('flow:'+$("#udpdirectionForm").val().toLowerCase()+';');
    }
});



// IP OPTIONS
$("#ttl").focusout(function(){
    if ($("#ttl").val() ==="") {
        $("#opttl").text('');
    } else if ($("#ttl").val().match(/^\d+$/g) !== null && $("#ttlevaluator").val() !== "") {
        $("#opttl").text('ttl:'+$("#ttlevaluator").val().replace('=','')+$("#ttl").val() +';');
    } else {
        $("#ttl").clearQueue();
        $("#ttl").val("");
        $("#ttl").effect("shake");
        $("#opttl").text('');
    }
});

$('body').on('change', '#ttlevaluator', function(){
    if ($("#ttl").val() ==="") {
        $("#opttl").text('');
    } else if ($("#ttl").val().match(/^\d+$/g) !== null && $("#ttlevaluator").val() !== "") {
        $("#opttl").text('ttl:'+$("#ttlevaluator").val().replace('=','')+$("#ttl").val() +';');
    } else {
        $("#ttl").clearQueue();
        $("#ttl").val("");
        $("#ttl").effect("shake");
        $("#opttl").text('');
    }
});

$('body').on('change', '#ipprotoevaluator', function(){
    if ($("#ipprotofield").val() ==="") {
        $("#opipprotocol").text('');
    } else if ($("#ipprotofield").val().match(/^\d+$/g) !== null && $("#ipprotoevaluator").val() !== "") {
        $("#opipprotocol").text('ip_proto:' + $("#ipprotoevaluator").val().replace('=','') + $("#ipprotofield").val() +';');
    } else {
        $("#ipprotofield").clearQueue();
        $("#ipprotofield").val("");
        $("#ipprotofield").effect("shake");
        $("#opipprotocol").text('');
    }
});

$("#ipprotofield").focusout(function(){
    if ($("#ipprotofield").val() ==="") {
        $("#opipprotocol").text('');
    } else if ($("#ipprotofield").val().match(/^\d+$/g) !== null && $("#ipprotoevaluator").val() !== "") {
        $("#opipprotocol").text('ip_proto:' + $("#ipprotoevaluator").val().replace('=','') + $("#ipprotofield").val() +';');
    } else {
        $("#ipprotofield").clearQueue();
        $("#ipprotofield").val("");
        $("#ipprotofield").effect("shake");
        $("#opipprotocol").text('');
    }
});

//misc options
//datasize
$('body').on('change', '#datasizeEval', function(){
    if ($("#datasize").val() === '' || $("#datasizeEval").val() === '') {
        $("#opdatasize").text('');
    } else if ($("#datasize").val().match(/^\d+$/g) === null) {
        $("#opdatasize").text('');
        $("#datasize").clearQueue();
        $("#datasize").val('');
        $("#datasize").effect('shake');
    } else {
        $("#opdatasize").text('dsize:'+$("#datasizeEval").val().replace('=','')+$("#datasize").val()+';');
    }
});

$("#datasize").focusout(function(){
    if ($("#datasize").val() === '' || $("#datasizeEval").val() === '') {
        $("#opdatasize").text('');
    } else if ($("#datasize").val().match(/^\d+$/g) === null) {
        $("#opdatasize").text('');
        $("#datasize").clearQueue();
        $("#datasize").val('');
        $("#datasize").effect('shake');
    } else {
        $("#opdatasize").text('dsize:'+$("#datasizeEval").val().replace('=','')+$("#datasize").val()+';');
    }
});
//reference type
$('body').on('change', '#reftype', function(){
    if ($("#referencetext").val() === '' || $("#reftype").val() === '') {
        $("#opreference").text('');
    } else if ($("#referencetext").val().match(/(?:\"|\'|\;|\:|\)|\(|\\|\||\`|\$|\&|\^|\%|\#|\!|\+|\=|\[|\]|\}|\{)/g) !== null) {
        $("#opreference").text('');
        $("#referencetext").clearQueue();
        $("#referencetext").val('');
        $("#referencetext").effect('shake');
    } else {
        $("#opreference").text('reference:'+$("#reftype").val().toLowerCase()+","+$("#referencetext").val()+';');
    }
});

$("#referencetext").focusout(function(){
    if ($("#referencetext").val() === '' || $("#reftype").val() === '') {
        $("#opreference").text('');
    } else if ($("#referencetext").val().match(/(?:\"|\'|\;|\:|\)|\(|\\|\||\`|\$|\&|\^|\%|\#|\!|\+|\=|\[|\]|\}|\{)/g) !== null) {
        $("#opreference").text('');
        $("#referencetext").clearQueue();
        $("#referencetext").val('');
        $("#referencetext").effect('shake');
    } else {
        $("#opreference").text('reference:'+$("#reftype").val().toLowerCase()+","+$("#referencetext").val()+';');
    }
});

function referenceUpdater() {
    if ($("#count").val() === "" || $("#seconds").val() === ""  || $("#thresholdtype").val() === ""  || $("#trackby").val() === "" ) {
        $("#opthreshold").text('');
    } else if ($("#count").val().match(/^\d+$/g) === null) {
        $("#count").clearQueue();
        $("#count").effect('shake');
        $("#count").val('');
    } else if ($("#seconds").val().match(/^\d+$/g) === null) {
        $("#seconds").clearQueue();
        $("#seconds").effect('shake');
        $("#seconds").val('');
    } else {
        $("#opthreshold").text('threshold:type '+$('#thresholdtype').val()+', track '+$("#trackby").val()+', count '+$("#count").val()+' , seconds '+$("#seconds").val()+';');
    }
};

//referencetext
$('body').on('change', '#thresholdtype', function(){
    referenceUpdater();
});

$('body').on('change', '#trackby', function(){
    referenceUpdater();
});

$("#count").focusout(function(){
    referenceUpdater();
});

$("#seconds").focusout(function(){
    referenceUpdater();
});

String.prototype.hexEncode = function(){
    var hex, i;

    var result = "";
    for (i=0; i<this.length; i++) {
        hex = this.charCodeAt(i).toString(16);
        result += ("000"+hex).slice(-4);
    }

    return result
}

$("#contentundo").click(function(){
    var thearray = $("#opcontentContainer").text().split('content:');
    thearray.pop();
    $("#opcontentContainer").text(thearray.join('content:'));
});

$(".diditinput").focusout(function() {
    if ($("#" + this.id).val().match(/^\d+$/g) !== null || $("#" + this.id).val() === "") {

    } else {
        $("#" + this.id).clearQueue();
        $("#" + this.id).effect('shake');
        $("#" + this.id).val('');
    }
});

//Content adder
$("#contentcheck").click(function(){
    if ($('#theoffset').val() !== '' && $('#theoffset').val().match(/^\d+$/g) !== null) {
        var theoffset = ' offset: ' + $('#theoffset').val() + ';';
    } else {
        var theoffset = '';
    }
    if ($('#thedepth').val() !== '' && $('#thedepth').val().match(/^\d+$/g) !== null) {
        var thedepth = ' depth: '  + $('#thedepth').val() + ';';
    } else {
        var thedepth = '';
    }
    if ($("#content1nocase").is(':checked')) {
        var nocase = ' nocase;';
    } else {
        var nocase = '';
    }
    if ($("#content1uri").is(':checked')) {
        var uri = ' http_uri;';
    } else {
        var uri = '';
    }
    if ($("#content1not").is(':checked')) {
        var not = '!';
    } else {
        var not = '';
    }

    if ($("#thecontent").val() !== "") {
        var beforecontent = $("#thecontent").val();
        var finalContent = '';
        for (var i = 0; i < beforecontent.length; i++) {
            if (beforecontent[i].match(/(?:\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\)|\(|\-|\_|\=|\+|\]|\[|\}|\{|\|\;|\:|'|"|\,|\<|\.|\>|\/|\?|\s)/g) !== null) {
                finalContent += '|'+beforecontent[i].hexEncode().split('00')[1]+'|';
            } else {
                finalContent += beforecontent[i];
            }
            if (parseInt(i) === (beforecontent.length -1)) {
                if ($("#opcontentContainer").text() === ""){
                    $("#opcontentContainer").text('content:'+not+'"'+finalContent.replace(/\|\|/g," ") + '";'+theoffset+thedepth+ nocase + uri);
                } else {
                    $("#opcontentContainer").text($("#opcontentContainer").text() + ' content:'+not+'"'+finalContent.replace(/\|\|/g," ") + '";'+theoffset+thedepth+ nocase + uri);
                }
            }
        }
    } else {
        $("#contentcheck").effect('shake');
    }
});


//pcre:"/TESTREGEX/ismxG";
//regexhandler
$("#pcrecheck").click(function(){

    if ($("#redotal").is(':checked')) {
        var redotal = 's';
    } else {
        var redotal = '';
    }
    if ($("#renocase").is(':checked')) {
        var renocase = 'i';
    } else {
        var renocase = '';
    }
    if ($("#regreedy").is(':checked')) {
        var regreedy = 'G';
    } else {
        var regreedy = '';
    }
    if ($("#renewline").is(':checked')) {
        var renewline = 'm';
    } else {
        var renewline = '';
    }
    if ($("#rewhitespace").is(':checked')) {
        var rewhitespace = 'x';
    } else {
        var rewhitespace = '';
    }

    var theregex = $("#theregex").val() + '/';
    if ($("#theregex").val() !== "" && $("#oppcre").text() === "") {
        $("#oppcre").text($("#oppcre").text() + ' pcre:"/' + theregex + renocase + redotal + renewline + rewhitespace + regreedy +  '";');
    } else if ($("#theregex").val() !== "") {
        $("#oppcre").text($("#oppcre").text() + ' pcre:"/' + theregex + renocase + redotal + renewline + rewhitespace + regreedy +  '";');
    } else {
        $("#pcrecheck").effect('shake');
    }
});

$("#pcreundo").click(function(){
    var thearray = $("#oppcre").text().split('pcre:');
    thearray.pop();
    $("#oppcre").text(thearray.join('pcre:'));
});


var wildcard = false;
var theplus = false;
$(".flagoptions").click(function(){
    if (this.id === 'wildcard') {
        if (wildcard) {
            $(".flagoptions").prop('checked', false);
            wildcard = false;
        } else {
            $(".flagoptions").prop('checked', false);
            $('#' + this.id).prop('checked', true);
            wildcard = true;
        }
    } else {
        if (theplus) {
            $(".flagoptions").prop('checked', false);
            theplus = false;
        } else {
            $(".flagoptions").prop('checked', false);
            $('#' + this.id).prop('checked', true);
            theplus = true;
        }
    }
});

function CopyToClipboard(containerid) {
  if (document.selection) { 
      var range = document.body.createTextRange();
      range.moveToElementText(document.getElementById(containerid));
      range.select().createTextRange();
      document.execCommand("Copy"); 

  } else if (window.getSelection) {
      var range = document.createRange();
       range.selectNode(document.getElementById(containerid));
       window.getSelection().addRange(range);
       document.execCommand("Copy");
       /*
       //$("#GlobalResults").text("Copied To Clipboard");
       $("#GlobalResults").text("");
       $("#GlobalResults").clearQueue();
       $("#GlobalResults").css('display','block');
       $("#GlobalResults").fadeIn(400);
      setTimeout(function() {
          $("#GlobalResults").fadeOut(400);
          $("#GlobalResults").css('display','none');
          $("#GlobalResults").text("");
      }, 400);
      */
      
  }
  //alert(window.clipboardData.getData('Text'));
};


var app = angular.module('swFrontendDpiRuleNew', ['ngCookies']);

app.controller('swFrontendDpiRuleNewCtrl', function($http, $scope,$rootScope, $window, $cookieStore,dpiServices) {
//app.controller('swFrontendDpiRuleNewCtrl', function($scope,$http) {
    $scope.actions = [{"id":"","name":"Action"},
                      {"id":"alert","name":"alert"},
                      {"id":"log","name":"log"},
                      {"id":"pass","name":"pass"},
                      {"id":"activate","name":"activate"},
                      {"id":"dynamic","name":"dynamic"},
                      {"id":"drop","name":"drop"},
                      {"id":"reject","name":"reject"},
                      {"id":"sdrop","name":"sdrop"}
                      ];
     
    $scope.protocols = [{"id":"","name":"Protocol"},
                      {"id":"tcp","name":"tcp"},
                      {"id":"icmp","name":"icmp"},
                      {"id":"udp","name":"udp"},
                      {"id":"ip","name":"ip"}
                      ];   
                      
    $scope.httpstatuscodes=["100","101",
                            "200","201","202","203","204","205","206",
                            "300","301","302","303","304","305","306","307",
                            "400","401","402","403","404","405","406","407","408","409","410","411","412","413","414","415","416","417",
                            "500","501","502","503","504","505"];

     
    $scope.httpmethods=["GET","POST","HEAD","TRACE","PUT","DELETE","CONNECT"];
    
    $scope.tcpdirections=["FROM_SERVER","TO_SERVER","TO_CLIENT","FROM_CLIENT"];
    
    $scope.tcpstatus=["established","stateless","not_established"];
    
    $scope.reftypes=["URL","CVE","BUG","MSB","NESS","ARAC","OSVD","MCAF"];
    
    $scope.priorities=["1","2","3","4","5"];
    
    $scope.isInputReady=false;
    $scope.sid="0001234";
    $scope.rev="1";
    $scope.categories=[{"id":"attack-responses.rules11","title":"attack-responses.rules11"},{"id":"backdoor.rules","title":"backdoor.rules"}];//[{"id":"attack-responses.rules","title":"attack-responses.rules"},{"id":"backdoor.rules","title":"backdoor.rules"},{"id":"bad-traffic.rules","title":"bad-traffic.rules"},{"id":"chat.rules","title":"chat.rules"},{"id":"ddos.rules","title":"ddos.rules"},{"id":"deleted.rules","title":"deleted.rules"},{"id":"dns.rules","title":"dns.rules"},{"id":"dos.rules","title":"dos.rules"},{"id":"exploit.rules","title":"exploit.rules"},{"id":"finger.rules","title":"finger.rules"},{"id":"ftp.rules","title":"ftp.rules"},{"id":"icmp-info.rules","title":"icmp-info.rules"},{"id":"icmp.rules","title":"icmp.rules"},{"id":"imap.rules","title":"imap.rules"},{"id":"info.rules","title":"info.rules"},{"id":"misc.rules","title":"misc.rules"},{"id":"multimedia.rules","title":"multimedia.rules"},{"id":"mysql.rules","title":"mysql.rules"},{"id":"netbios.rules","title":"netbios.rules"},{"id":"nntp.rules","title":"nntp.rules"},{"id":"other-ids.rules","title":"other-ids.rules"},{"id":"p2p.rules","title":"p2p.rules"},{"id":"policy.rules","title":"policy.rules"},{"id":"pop2.rules","title":"pop2.rules"},{"id":"pop3.rules","title":"pop3.rules"},{"id":"porn.rules","title":"porn.rules"},{"id":"rpc.rules","title":"rpc.rules"},{"id":"rservices.rules","title":"rservices.rules"},{"id":"scan.rules","title":"scan.rules"},{"id":"smtp.rules","title":"smtp.rules"},{"id":"snmp.rules","title":"snmp.rules"},{"id":"sql.rules","title":"sql.rules"},{"id":"telnet.rules","title":"telnet.rules"},{"id":"tftp.rules","title":"tftp.rules"},{"id":"virus.rules","title":"virus.rules"},{"id":"web-cgi.rules","title":"web-cgi.rules"},{"id":"web-client.rules","title":"web-client.rules"},{"id":"web-coldfusion.rules","title":"web-coldfusion.rules"},{"id":"web-frontpage.rules","title":"web-frontpage.rules"},{"id":"web-iis.rules","title":"web-iis.rules"},{"id":"web-misc.rules","title":"web-misc.rules"},{"id":"web-php.rules","title":"web-php.rules"},{"id":"x11.rules","title":"x11.rules"}];
        
    
    $scope.$watch(function(){      
      if (dpiServices.isValidValue($scope.categoryForm) 
        && dpiServices.isValidValue($scope.actionForm) 
        && dpiServices.isValidValue($scope.protoForm) 
        && dpiServices.isValidValue($scope.srcip) 
        && dpiServices.isValidValue($scope.dstip) 
        && dpiServices.isValidValue($scope.headermessage)         
        //&& dpiServices.isValidValue($scope.srcport) 
        //&& dpiServices.isValidValue($scope.dstport) 
        ) {
        $scope.isInputReady=true;
        
      }else {
        $scope.isInputReady=false;
      }

      //console.log($scope.isInputReady);
      return;
    });

    $http.get("/sw-frontend-dpi-api.php?action=file").success(function(_data){
      //$scope.categories = _data;
      $scope.categories = [{"id":"","title":"Category"}];
      $scope.categories=$scope.categories.concat(_data);
			
		})

    $http.get("/sw-frontend-dpi-api.php?action=nextsid").success(function(_data){
      var mysid=1;
      if (_data.length>0) {
        mysid=_data[0].sid;
      }
      $scope.sid=mysid
    })    

    $scope.saveRule = function() {

      //$scope.message = 'Fetched after 3 seconds';
      if (dpiServices.isValidValue($scope.actionForm)) {
        console.log("actionForm:OK");
      }else{
        console.log("actionForm:NG1");
      }

      if (dpiServices.isValidValue($scope.protoForm)) {
        console.log("protoForm:OK");
      }else{
        console.log("protoForm:Ng2");
      }


/*
      console.log($scope.actionForm);
      console.log($scope.protoForm);
      console.log($scope.srcip);
      console.log($scope.srcport);
      console.log($scope.dstip);
      console.log($scope.dstport);
      console.log($scope.sid);
      console.log($scope.rev);
*/

      //console.log("1");
      var dpirule = $( "#innerRuleOutput" ).text();
      //console.log("2");
      dpirule=dpirule.trim();
      // console.log("3");
      dpirule = dpirule.replace(/(\r\n|\n|\r)/gm,"");
      // console.log("4");
      dpirule = dpirule.replace(/(\n|\r)/gm,"");
       console.log("5");
      // console.log(dpirule);
      //dpirule="alert tcp $HOME 80 -> $HOME 80 (msg:\"aaaaaaaaaaaaaaaa\";content:\"bbbbb\";priority:1;sid:00003460;rev:1; )";
      console.log("dpirule=" + dpirule);
      console.log($scope.categoryForm);
     // dpirule = dpirule.replace(/\\/gm,"\\\\");
     // dpirule = dpirule.replace(/\"/gm,"\\\"");
      var rule = {
                "action": "newrule",
                "rule": dpirule,
                "file": $scope.categoryForm,
                "api_key": "NNHvkmfHJR5HBSULuLlDzGHDc7dM4obQDr5GzQIx5nY",
                "token": "qWb311cvOE73BL32ZpROvB83XocFux2bs"
      }
      //console.log(rule);
      //console.log( JSON.stringify(rule));
      console.log("Step4");

    //console.log(JSON.stringify(rule));
    $scope.aws=$window.confirm("Are you sure to save this new rule?" );

    if ( $scope.aws ) {

        $http({
           url: '/sw-frontend-dpi-api.php?action=newrule',
           dataType: 'json',
           method: "POST",
           data: {action: 'newrule',
                   rule: dpirule,
                   file: $scope.categoryForm,
                   api_key: 'NNHvkmfHJR5HBSULuLlDzGHDc7dM4obQDr5GzQIx5nY',
                   token: 'qWb311cvOE73BL32ZpROvB83XocFux2bs' 
                  },
           headers: {
               "Content-Type": "application/json"
           }

       }).success(function(response){
           console.log(response);
           alert("Rule has bee added.");
           $window.location.reload();///sw-frontend-dpi-rule-list");
       }).error(function(error){
           $scope.error = error;
       });
/*

        var res = $http.post('/sw-frontend-dpi-api.php?action=newrule', rule);

	res.success(function(data) {
                    console.log(data);
		});

	res.error(function(data, status, headers, config) {
			alert( "failure message: " + JSON.stringify({data: data}));
		});
*/      
       console.log("posted9");
    }



    }
});
         


angular.module('swFrontendDpiRuleNew').service('dpiServices',function($http, $rootScope, $window, $cookieStore,$timeout) {
  this.isValidValue=function(val){  
    if (val != null && val != "" ) return true;
    return false;
  }
  
  this.logout = function() {
    $cookieStore.remove("accessToken");
    $cookieStore.remove("userId");
    $window.location.reload();
  }
});

angular.module('swFrontendDpiRuleNew').service('helloService',function($http, $rootScope, $window, $cookieStore,$timeout){
  this.sayHello=function(name){
    $timeout(function(){
      alert('Hello '+name);
    },2000);
  }
});


