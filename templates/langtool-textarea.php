

<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/oldceeleldhonbafppcapldpdifcinji">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
    .window img{
        position: absolute;
        left:calc(50% - 15px);
        height: 30px;
        width: 30px;
        display: none;
    }
    #editorHeader{
        background: #1B78EE;
        color: #fff;
        padding: 5px 15px;
    }

    #editorFooter{
        background: #1B78EE;
        color: #fff;
        padding: 15px 15px;
        display: flex;
        justify-content: center;
    }
    #editorFooter button{
        margin-right: 5px;
        font-size: 15px;
        letter-spacing: 0.8px;
        text-align: center;
        color: #fff;

        padding: 10px 20px;
        border-radius: 4px;
        border: 1px solid #fff;
        background-color: transparent;
        cursor: pointer;
    }
    #btnCheck{
        border: 0;
        font-size: 15px;
        letter-spacing: 0.8px;
        text-align: center;
        color: #fff;
        padding: 10px 20px;
        border-radius: 4px;
        background-color: #20d78d;
    }
    .container-design{
        width: 300px;
    }
    .container-design div{
        background-color: #ffffff;
        -webkit-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
        -moz-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
    }
    #txtLangID{
        min-height: 300px;
        padding: 10px;
        background-color:white;
    }
    #txtLangID p{
        text-align: left;
    }
    .misspelling{
        background-color: #ffcccc;
    }
    .grammar{
        background-color: #fee481;
    }
    .style{
        background-color: #c9cdff;
    }
    .pointer{
        cursor: pointer;
    }
    .container-design{
        cursor: default;
    }
    .checker-heading{
        border-bottom: 1px solid #999999;
    }
    #checker_menu{
        width: 100%;
    }
    .editor{
        width: 100%;
    }
    .window{
        margin: auto;
        max-width: 900px;
        overflow: hidden;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        -webkit-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
        -moz-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
    }
    .match-value td a{
        position: relative;
        padding: 3px 0 4px 0;
    }
    .replacement-menu{
        padding-left: 10px;
        font-size: 14px;
        letter-spacing: 0.6px;
        padding-top: 4.3px;
        text-align: left;
        color: #7f7f7f;
        padding-bottom: 4.3px;
        width: 100%;
    }
</style>
<body>
<div id="editor">
    <div class="window">
        <img id="getting-data" src="wp-content/plugins/language-tool/templates/source.gif" width="30%" draggable="false"/>
        <div id="editorHeader">
            <h1>Grammar and Spell Checker</h1>
        </div>
        <div id="txtLangID" class="txtLangClass" contenteditable="true" spellcheck="false">
            <p>LanguageTool offers spell and grammar checking. Just paste your text here and click the 'Check Text' button. Click the colored phrases for details on potential errors. or use this text too see an few of of the problems that LanguageTool can detecd. What do you thinks of grammar checkers? Please not that they are not perfect. Style issues get a blue marker: It's 5 P.M. in the afternoon. The weather was nice on Thursday, 27 June 2017.</p>
        </div>
        <div id="editorFooter">
            <button id="btnTrash"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></button>
            <input type="button" value="Check" id="btnCheck">
        </div>
    </div>
</div>
    <div id="checker-container" class="container-design">
        <div id="showMeTest" class="" style="position: absolute; z-index: 200000; display: none; width: 300px;">
            <div role="presentation" id="menu_checktext_spellcheckermenu_co" class="mceMenu mceNoIcons defaultSkin" style="left: 0px; max-width: 100vw;">
                <table id="checker_menu">
                    <tbody>
                        <tr>
                            <td class="checker-heading">
                                <span id="checker-message" class="replacement-menu">This sentence does not start with an uppercase letter</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a id="another_replacement" class="option">
                                    <span  class="replacement-menu">(another replacement)</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a id="ignore_error" class="option">
                                    <span class="replacement-menu">Ignore this type of error</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a id="suggest_word" class="option">
                                    <span class="replacement-menu">Suggest word for dictionary...</span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
    let check = document.getElementById("btnCheck");
    let trash = document.getElementById("btnTrash");
    let header = document.getElementById("editorHeader");
    let loading = document.getElementById("getting-data");
    let editor = document.getElementById("txtLangID");
    let heading = document.getElementById("checker-message");
    let checkMenu = document.getElementById("checker_menu");
    let another = document.getElementById("another_replacement");
    let ignore = document.getElementById("ignore_error");
    let suggest = document.getElementById("suggest_word");
    let elementToShow = document.getElementById('showMeTest');
    let text = errorId = "", language = "auto";
    let matches = {}, errorsDetected = [], errorsObject;
    let extractStart = 0, extractEnd = 0;
    let extractedWord = languageCode = "";
    let cssClass;

    trash.addEventListener("click", function() {
        document.querySelector("#txtLangID p").innerText = "";
    });

    check.addEventListener("click", checkGrammarAndSpelling);

    function checkGrammarAndSpelling() {
        text = document.querySelector("#txtLangID p");
        let result = text.innerText;
        document.querySelector("#txtLangID p").innerHTML = result;
        editor.style.pointerEvents = "none";
        editor.style.backgroundColor = "#eceae9";
        loading.style.display = "block";
        loading.style.top = header.offsetHeight+(editor.offsetHeight * 0.5) +'px';
        const xhr = new XMLHttpRequest();
        xhr.onload = function(){
            let obj = JSON.parse(xhr.responseText);
            matches = obj.matches;
            console.log(obj);
            languageCode = obj.language.detectedLanguage.code;
            for (let index = obj.matches.length-1; index >= 0; index--) {

                //checking Issue type
                if (obj.matches[index].rule.issueType=="misspelling") {
                    cssClass = "misspelling pointer";
                }
                if (obj.matches[index].rule.issueType=="grammar" || obj.matches[index].rule.issueType=="typographical" || obj.matches[index].rule.issueType=="inconsistency" || obj.matches[index].rule.issueType=="duplication") {
                    cssClass = "grammar pointer";
                }
                if(obj.matches[index].rule.issueType=="style"){
                    cssClass = "style pointer";
                }

                //Extract string
                let startInd = obj.matches[index].offset;
                let endInd = obj.matches[index].offset + obj.matches[index].context.length;
                let extractedText = text.innerText.slice(startInd, endInd);

                //Insert the string inside span element
                let span = '<span id="'+index+'" class="'+cssClass+' error-detected">'+extractedText+'</span>';

                //push text errors to array for ignoring and and unwrapping
                errorsDetected.push(extractedText);
                
                //Concatinate span 
                result = result.substring(0, startInd) + span + result.substring(endInd);
            }
            errorsDetected.reverse();
            loading.style.display = "none";
            editor.style.removeProperty("pointer-events");
            editor.style.removeProperty("background-color");
            text.innerHTML = result;
            
            //Get all errors
            errorsObject = document.querySelectorAll(".error-detected");
            console.log(errorsObject);
        };
        xhr.open("POST", "https://languagetool.org/api/v2/check");
        xhr.setRequestHeader("Content-Type", " application/x-www-form-urlencoded");
        xhr.send("text="+text.innerText+"&language="+language+"&enabledOnly=false");
    }

    function showReplacement(index) {
        let matchValue = document.querySelectorAll('.match-value');
        for (let index = 0; index < matchValue.length; index++) {
            matchValue[index].remove();
        }

        
        if(matches[index].rule.issueType=="inconsistency"){
            document.querySelector('#another_replacement span').innerText = "Replace with...";
        }
        else{
            document.querySelector('#another_replacement span').innerText = "(another replacement)";
        }
        
        suggest.parentNode.parentNode.style.visibility = "collapse";
        if (matches[index].rule.issueType=="misspelling") {
            suggest.parentNode.parentNode.style.visibility = "visible";
        }

        heading.innerHTML = matches[index].message;
        for (let repIndex = 0; repIndex < matches[index].replacements.length; repIndex++) {
            let row = checkMenu.insertRow(1+repIndex);
            row.className  = "match-value";
            let cell = row.insertCell(0);
            cell.innerHTML = '<a id="'+repIndex+'"><span id="value-'+repIndex+'" class="replacement-menu possible-value">'+matches[index].replacements[repIndex].value+'</span></a>';
            document.getElementById(repIndex).addEventListener('change', function(e) {
                
            });
        }
    }

    another.addEventListener('click', function() {
        let word = prompt("Replace with:", extractedWord);
        if (word == null || word == "") {
            closeMenu();
        }
        else{
            unwrapSpan(errorId, word);
        }
    });

    ignore.addEventListener('click', function(){
        unwrapSpan(errorId);
        errorsDetected[errorId] = "Error Ignored";
    });

    function unwrapSpan(index, text) {
        let spanElement = document.getElementById(index);
        if ( text == null || text == "") {
            text = spanElement.textContent;
        }
        spanElement.parentNode.replaceChild(document.createTextNode(text), spanElement);
        closeMenu();
    }

    suggest.addEventListener('click', function() {
        window.open("https://community.languagetool.org/suggestion?word="+extractedWord+"&lang="+languageCode);
    });

    function getWord(index) {
        extractStart = matches[index].offset;
        extractEnd = matches[index].offset + matches[index].context.length;
        extractedWord = text.innerText.slice(extractStart, extractEnd);
    }

    window.addEventListener('click', function(e){
        if (!document.getElementById('showMeTest').contains(e.target)){
            closeMenu()
        }
        if (e.target.tagName=="SPAN") {
            if (e.target.id!="" || e.target.id!="") {
                if (document.getElementById(e.target.id).classList.contains("error-detected")) {
                    errorId = e.target.id;
                    showReplacement(errorId);
                    getWord(errorId);
                    //Assigned position
                    elementToShow.style.top = `${e.target.offsetTop + e.target.offsetHeight}px`;
                    elementToShow.style.left = `${e.target.offsetLeft}px`;
                    if (getScreenWidth()<e.target.offsetLeft) {
                        elementToShow.style.left = `${getScreenWidth()}px`;
                    }
                    //display replacement menu
                    elementToShow.style.display = "block";
                }
                if (document.getElementById(e.target.id).classList.contains("possible-value")) {
                    document.getElementById(errorId).innerText = document.getElementById(e.target.id).innerText;
                    unwrapSpan(errorId);
                }
            }
        }
        
    });

    editor.addEventListener("input", function() {
        for (let e = 0; e < errorsObject.length; e++) {
            if (errorsObject[e].innerText.length!=errorsDetected[e].length && errorsDetected[e]!="Error: Ignored") {
                unwrapSpan(e);
                errorsDetected[e] = "Error: Ignored";
                break;
            }
        }
    }, false);

    function closeMenu(){
        elementToShow.style.display = "none";
    }

    window.addEventListener('load', (event) => {
        if (document.querySelector("#txtLangID p").innerText != "") {
            checkGrammarAndSpelling();
        }
    });

    window.addEventListener("resize", getScreenWidth);

    function getScreenWidth() {
        loading.style.top = header.offsetHeight+(editor.offsetHeight * 0.5) +'px';
        return document.querySelector('.window').offsetWidth - 310 + document.querySelector('.window').offsetLeft;
    }

    //get caret position
    function getCaretCharacterOffsetWithin(element = document.getElementById("txtLangID")) {
        var caretOffset = 0;
        var doc = element.ownerDocument || element.document;
        var win = doc.defaultView || doc.parentWindow;
        var sel;
        if (typeof win.getSelection != "undefined") {
            sel = win.getSelection();
            if (sel.rangeCount > 0) {
                var range = win.getSelection().getRangeAt(0);
                var preCaretRange = range.cloneRange();
                preCaretRange.selectNodeContents(element);
                preCaretRange.setEnd(range.endContainer, range.endOffset);
                caretOffset = preCaretRange.toString().length;
            }
        } else if ( (sel = doc.selection) && sel.type != "Control") {
            var textRange = sel.createRange();
            var preCaretTextRange = doc.body.createTextRange();
            preCaretTextRange.moveToElementText(element);
            preCaretTextRange.setEndPoint("EndToEnd", textRange);
            caretOffset = preCaretTextRange.text.length;
        }
        return caretOffset;
    }

    function showCaretPos() {
        console.log("Caret position: " + getCaretCharacterOffsetWithin());
    }

    document.body.onkeyup = showCaretPos;
    document.body.onmouseup = showCaretPos;

</script>