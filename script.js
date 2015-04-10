/**
 * Created by wlane on 12/11/14.
 */
//function progressHandlingFunction(e){
//    if(e.lengthComputable){
//        $('progress').attr({value:e.loaded,max:e.total});
//    }
//}
function parseURL(url) {
    var parser = document.createElement('a'),
        searchObject = {},
        queries, split, i;
    // Let the browser do the work
    parser.href = url;
    // Convert query string to object
    queries = parser.search.replace(/^\?/, '').split('&');
    for( i = 0; i < queries.length; i++ ) {
        split = queries[i].split('=');
        searchObject[split[0]] = split[1];
    }
    return {
        protocol: parser.protocol,
        host: parser.host,
        hostname: parser.hostname,
        port: parser.port,
        pathname: parser.pathname,
        search: parser.search,
        searchObject: searchObject,
        hash: parser.hash
    };
}
function extractFormIdFromParsedUrl(search)
{
    //Isolate the form ID + s
    var formId="";
    for(var i = 0; i < search.length; i ++)
    {
        if(search[i] == "="){
            i++;
            while(i < search.length) {
                formId += search[i];
                i++;
            }
        }
    }
    id = formId;
    return id;
}
function extractScoringSchemeNumFromFormIdString(id)
{
    //isolate the scoringSchemeNumber
    var scoringSchemeNum="";
    for(var i = 0; i< id.length; i++)
    {
        if(id[i] == "s"){
            i++;
            i++;
            scoringSchemeNum = id[i];
            break;
        }
    }
    return scoringSchemeNum;
}
$(function()
{
    // Variable to store your files
    var files;
    var testKey;
    var optional;

    var fileChosen = false;
    var testKeyChosen = false;
    var optionalChosen = false;

    var fileSuccess= false;
    var testKeySuccess = false;
    var optionalSuccess = false;

    //grab current alfWindow url, parse form Id, extract formId and scoringSchemaNum
    var url = window.location;
    var search = parseURL(url).search.toString();
    var formId = extractFormIdFromParsedUrl(search);
    var scoringSchemeNum = extractScoringSchemeNumFromFormIdString(formId);

    //---------------------------------------------------------------------------------------------------------------------
    //  Scoring Scheme number meaning:
    //  0 = entire test scored by instructor                                      (TEST file only)
    //  1 = entire test on testing center answer sheet                            (TEST and KEY files only)
    //  2 = Testing center scored portion and written instructor scored portion   (TEST, KEY, and ADDitional file files
    //---------------------------------------------------------------------------------------------------------------------

    // Add events
    $('input[id=file_upload]').on('change', prepareUpload);
    $('input[id=file_upload2]').on('change', prepareUpload2);
    $('input[id=file_upload3]').on('change', prepareUpload3); // ****fix
    $('form').on('submit', uploadFiles);

    // Grab the files and set them to our variable
    function prepareUpload(event)
    {
        files = event.target.files;
        fileChosen = true;
    }
    //grab the test file files, set them to a variable
    function prepareUpload2(event)
    {
        testKey= event.target.files;
        testKeyChosen = true;
    }
    //grab the test file files, set them to a variable
    function prepareUpload3(event)
    {
        optional= event.target.files;
        optionalChosen = true;
    }

    // Catch the form submit and upload the files
    function uploadFiles(event)
    {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

            //----------------------------------
            //----------------------------------
            //----------------------------------
            //------ Upload Test File ----------
            //----------------------------------
            //----------------------------------
            if(fileChosen) {
                document.getElementById("TestResult").innerHTML = " Test File Uploading, please wait...";

                var data = new FormData();
                $.each(files, function (key, value) {
                    data.append(key, value);
                });

                $.ajax({
                    url: 'cmis_upload.php?files&id=' + formId,
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'text',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function(message, response){
                        document.getElementById("TestResult").style.color = "Green";
                        document.getElementById("TestResult").innerHTML = " Test File Upload Successful!";
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Handle errors here
                        console.log('ERRORS: ' + textStatus);
                        document.getElementById("TestResult").innerHTML = "EROR";
                        // STOP LOADING SPINNER
                    }
                });
            }
            //---------------------------------
            //---------------------------------
            //---------------------------------
            // -- Now Upload the Test Key -----
            //---------------------------------
            //---------------------------------
            if(testKeyChosen) {

                document.getElementById("KeyResult").innerHTML = " Test Key Uploading, please wait...";

                // Create a formdata object and add the files
                var data2 = new FormData();
                $.each(testKey, function (key, value) {
                    data2.append(key, value);
                });

                $.ajax({
                    url: 'cmis_upload.php?files&id=' + formId,
                    type: 'POST',
                    data: data2,
                    cache: false,
                    dataType: 'text',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function(message, response){
                        document.getElementById("KeyResult").style.color = "Green";
                        document.getElementById("KeyResult").innerHTML = " Test Key Upload Successful!";
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Handle errors here
                        console.log('ERRORS: ' + textStatus);
                        // STOP LOADING SPINNER
                    }
                });
            }

        //-----------------------------------------
        //-----------------------------------------
        //-----------------------------------------
        // -- Now Upload the Optional Portion -----
        //-----------------------------------------
        //-----------------------------------------

        if(optionalChosen) {
            document.getElementById("OptionalResult").innerHTML = " Optional Uploading, please wait...";

            // Create a formdata object and add the files
            var data3 = new FormData();
            $.each(optional, function (key, value) {
                data3.append(key, value);
            });

            $.ajax({
                url: 'cmis_upload.php?files&id='+formId,
                type: 'POST',
                data: data3,
                cache: false,
                dataType: 'text',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(message, response){
                    document.getElementById("OptionalResult").style.color = "Green";
                    document.getElementById("OptionalResult").innerHTML = " Optional Upload Successful!";
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus);
                    // STOP LOADING SPINNER
                }
            });
        }
    }
});