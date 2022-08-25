
<link href="<?php echo base_url(); ?>Assets/highlight/themes/blackboard.css" rel="stylesheet" type="text/css" media="screen" />
<!--<link href="<?php echo base_url(); ?>Assets/highlight/themes/obsidian.css" rel="stylesheet" type="text/css" media="screen">-->
<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 1) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_php">Send SMS In PHP</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 2) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_csharp">Send SMS In C#</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 3) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_java">Send SMS In Java</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 4) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_java_xml">Send SMS In Java XML</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 10) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_java_jsp">Send SMS In jsp</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 5) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_python">Send SMS In Python</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 6) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_windows8">Send SMS In Windows 8</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 7) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_android">Send SMS In Android</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 8) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_ios">Send SMS In IOs</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 9) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/in_vb6">Send SMS In VB6</a>
            </li>
        </ul>
    </div>
</div>
</div>

<nav class="dlTool_nav visible-xs">
    <a href="#" class="navToggle_xs" title="show menu">
        <span class="navClosed"><i>show menu</i></span>
    </a>
    <a href="<?php echo base_url(); ?>api_docs/in_php">Send SMS In PHP</a>
    <a href="<?php echo base_url(); ?>api_docs/in_csharp">Send SMS In C#</a>
    <a href="<?php echo base_url(); ?>api_docs/in_java">Send SMS In Java</a>
    <a href="<?php echo base_url(); ?>api_docs/in_java_xml">Send SMS In Java XML</a>
    <a href="<?php echo base_url(); ?>api_docs/in_java_xml">Send SMS In jsp</a>
    <a href="<?php echo base_url(); ?>api_docs/in_python">Send SMS In Python</a>
    <a href="<?php echo base_url(); ?>api_docs/in_windows8">Send SMS In Windows 8</a>
    <a href="<?php echo base_url(); ?>api_docs/in_android">Send SMS In Android</a>
    <a href="<?php echo base_url(); ?>api_docs/in_ios">Send SMS In IOs</a>
    <a href="<?php echo base_url(); ?>api_docs/in_vb6">Send SMS In VB6</a>
</nav>

<div class="container">
    <div class="row">
        <?php
        // PHP
        if (isset($page_type) && $page_type && $page_type == 1) {
            ?>
<div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">PHP Sample Code For Send Text SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="php">
//Your authentication key
$authKey = "YourAuthKey";
//Multiple mobiles numbers separated by comma
$mobileNumber = "9999999";
//Sender ID,While using route4 sender id should be 6 characters long.
$senderId = "102234";
//Your message to send, Add URL encoding here.
$message = "Test message";
//Define route 
$route = "default";
//Prepare you post parameters
$postData = array(
    'authkey' => $authKey,
    'mobiles' => $mobileNumber,
    'message' => $message,
    'sender' => $senderId,
    'route' => $route
);
//API URL
$url = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_http.php";
// init the resource
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData
    //,CURLOPT_FOLLOWLOCATION => true
));
//Ignore SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//get response
$output = curl_exec($ch);
//Print error if any
if (curl_errno($ch)) {
    echo 'error:' . curl_error($ch);
}
curl_close($ch);
echo $output;
</code>                                    
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        <div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">PHP Sample Code For Send Voice SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="php">
//Your authentication key
$authKey = "YourAuthKey";
//Multiple mobiles numbers separated by comma
$mobileNumber = "9999999";
//Sender , Source mobile number
$sender = "9999999999";
//Your message to send, Add URL encoding here.
$message = urlencode("voice file url");
//Define route 
$route = "default";
//Duration in seconds.
$duration = "30";
//Prepare you post parameters
$postData = array(
    'authkey' => $authKey,
    'mobiles' => $mobileNumber,
    'message' => $message,
    'sender' => $senderId,
    'route' => $route,
    'duration' => $duration
);
//API URL
$url = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php";
// init the resource
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData
    //,CURLOPT_FOLLOWLOCATION => true
));
//Ignore SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//get response
$output = curl_exec($ch);
//Print error if any
if (curl_errno($ch)) {
    echo 'error:' . curl_error($ch);
}
curl_close($ch);
echo $output;
</code>                                    
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
            <?php
        }

        // C#
        if (isset($page_type) && $page_type && $page_type == 2) {
            ?>
<div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">C# Sample Code For Send Text SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="csharp">
//Your authentication key
string authKey = "YourAuthKey";
//Multiple mobiles numbers separated by comma
string mobileNumber = "9999999";
//Sender ID,While using route4 sender id should be 6 characters long.
string senderId = "102234";
//Your message to send, Add URL encoding here.
string message = HttpUtility.UrlEncode("Test message");
//Prepare you post parameters
StringBuilder sbPostData = new StringBuilder();
sbPostData.AppendFormat("authkey={0}", authKey);
sbPostData.AppendFormat("&mobiles={0}", mobileNumber);
sbPostData.AppendFormat("&message={0}", message);
sbPostData.AppendFormat("&sender={0}", senderId);
sbPostData.AppendFormat("&route={0}", "default");
try
{
    //Call Send SMS API
    string sendSMSUri = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_http.php";
    //Create HTTPWebrequest
    HttpWebRequest httpWReq = (HttpWebRequest)WebRequest.Create(sendSMSUri);
    //Prepare and Add URL Encoded data
    UTF8Encoding encoding = new UTF8Encoding();
    byte[] data = encoding.GetBytes(sbPostData.ToString());
    //Specify post method
    httpWReq.Method = "POST";
    httpWReq.ContentType = "application/x-www-form-urlencoded";
    httpWReq.ContentLength = data.Length;
    using (Stream stream = httpWReq.GetRequestStream())
    {
        stream.Write(data, 0, data.Length);
    }
    //Get the response
    HttpWebResponse response = (HttpWebResponse)httpWReq.GetResponse();
    StreamReader reader = new StreamReader(response.GetResponseStream());
    string responseString = reader.ReadToEnd();

    //Close the response
    reader.Close();
    response.Close();
}
catch (SystemException ex)
{
    MessageBox.Show(ex.Message.ToString());
}
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        <div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">C# Sample Code For Send Voice SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="csharp">
//Your authentication key
string authKey = "YourAuthKey";
//Multiple mobiles numbers separated by comma
string mobileNumber = "9999999";
//Sender , Source mobile number
string sender = "9999999999";
//Your message to send, Add URL encoding here.
string message = HttpUtility.UrlEncode("Voice File Url");
//Duration in seconds.
string duration = "30";

//Prepare you post parameters
StringBuilder sbPostData = new StringBuilder();
sbPostData.AppendFormat("authkey={0}", authKey);
sbPostData.AppendFormat("&mobiles={0}", mobileNumber);
sbPostData.AppendFormat("&message={0}", message);
sbPostData.AppendFormat("&sender={0}", sender);
sbPostData.AppendFormat("&route={0}", "default");
sbPostData.AppendFormat("&duration={0}", duration);
try
{
    //Call Send SMS API
    string sendSMSUri = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php";
    //Create HTTPWebrequest
    HttpWebRequest httpWReq = (HttpWebRequest)WebRequest.Create(sendSMSUri);
    //Prepare and Add URL Encoded data
    UTF8Encoding encoding = new UTF8Encoding();
    byte[] data = encoding.GetBytes(sbPostData.ToString());
    //Specify post method
    httpWReq.Method = "POST";
    httpWReq.ContentType = "application/x-www-form-urlencoded";
    httpWReq.ContentLength = data.Length;
    using (Stream stream = httpWReq.GetRequestStream())
    {
        stream.Write(data, 0, data.Length);
    }
    //Get the response
    HttpWebResponse response = (HttpWebResponse)httpWReq.GetResponse();
    StreamReader reader = new StreamReader(response.GetResponseStream());
    string responseString = reader.ReadToEnd();

    //Close the response
    reader.Close();
    response.Close();
}
catch (SystemException ex)
{
    MessageBox.Show(ex.Message.ToString());
}
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
            <?php
        }

        // Java
        if (isset($page_type) && $page_type && $page_type == 3) {
            ?>
<div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">Java Sample Code For Send Text SMS</h2>
                <div class="portlet-content">                               
                    <pre>
<code data-language="java">
//Your authentication key
String authkey = "YourAuthKey";
//Multiple mobiles numbers separated by comma
String mobiles = "9999999";
//Sender ID,While using route4 sender id should be 6 characters long.
String senderId = "102234";
//Your message to send, Add URL encoding here.
String message = "Test message";
//define route
String route="default";
//Prepare Url
URLConnection myURLConnection=null;
URL myURL=null;
BufferedReader reader=null;
//encoding message 
String encoded_message=URLEncoder.encode(message);
//Send SMS API
String mainUrl="<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_http.php";
//Prepare parameter string 
StringBuilder sbPostData= new StringBuilder(mainUrl);
sbPostData.append("authkey="+authkey); 
sbPostData.append("&mobiles="+mobiles);
sbPostData.append("&message="+encoded_message);
sbPostData.append("&route="+route);
sbPostData.append("&sender="+senderId);
//final string
mainUrl = sbPostData.toString();
try
{
    //prepare connection
    myURL = new URL(mainUrl);
    myURLConnection = myURL.openConnection();
    myURLConnection.connect();
    reader= new BufferedReader(new InputStreamReader(myURLConnection.getInputStream()));
    //reading response 
    String response;
    while ((response = reader.readLine()) != null) 
    //print response 
    System.out.println(response);

    //finally close connection
    reader.close();
} 
catch (IOException e) 
{ 
    e.printStackTrace();
} 
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        
        <div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">Java Sample Code For Send Voice SMS</h2>
                <div class="portlet-content">                               
                    <pre>
<code data-language="java">
//Your authentication key
String authkey = "YourAuthKey";
//Multiple mobiles numbers separated by comma
String mobiles = "9999999";
//Sender , Source mobile number
String sender = "9999999999";
//Your message to send, Add URL encoding here.
String message = "Voice File Url";
//define route
String route="default";
//Duration in seconds.
string duration = "30";

//Prepare Url
URLConnection myURLConnection=null;
URL myURL=null;
BufferedReader reader=null;
//encoding message 
String encoded_message=URLEncoder.encode(message);
//Send SMS API
String mainUrl="<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php";
//Prepare parameter string 
StringBuilder sbPostData= new StringBuilder(mainUrl);
sbPostData.append("authkey="+authkey); 
sbPostData.append("&mobiles="+mobiles);
sbPostData.append("&message="+encoded_message);
sbPostData.append("&route="+route);
sbPostData.append("&sender="+sender);
sbPostData.append("&sender="+duration);
//final string
mainUrl = sbPostData.toString();
try
{
    //prepare connection
    myURL = new URL(mainUrl);
    myURLConnection = myURL.openConnection();
    myURLConnection.connect();
    reader= new BufferedReader(new InputStreamReader(myURLConnection.getInputStream()));
    //reading response 
    String response;
    while ((response = reader.readLine()) != null) 
    //print response 
    System.out.println(response);

    //finally close connection
    reader.close();
} 
catch (IOException e) 
{ 
    e.printStackTrace();
} 
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
            <?php
        }

        // Java With XML
        if (isset($page_type) && $page_type && $page_type == 4) {
            ?>
<div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">Java XML Sample Code For Send Text SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="java">
import java.io.*;
import java.net.URL;
import java.net.HttpURLConnection;
class Functions
{
    public static String hitUrl(String urlToHit, String param)
    {
        try
        {
            URL url = new URL(urlToHit);
            HttpURLConnection http = (HttpURLConnection)url.openConnection();
            http.setDoOutput(true);
            http.setDoInput(true);
            http.setRequestMethod("POST");
            DataOutputStream wr = new DataOutputStream(http.getOutputStream());
            wr.writeBytes(param);
            wr.flush();
            wr.close();
            http.disconnect();  
            BufferedReader in = new BufferedReader(new InputStreamReader(http.getInputStream()));
            String inputLine;
            if ((inputLine = in.readLine()) != null)
            {
                in.close();
                return inputLine;
            }
            else
            {
                in.close();
                return "-1";
            }
        }
        catch(Exception e)
        {
            System.out.println("Exception Caught..!!!");
            e.printStackTrace();
            return "-2";
        }
    }
}

public class HitXmlData 
{
    public static void main(String[] args) 
    {
        String strUrl = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_xml.php";
        String xmlData = "data=
            <MESSAGE>
            <AUTHKEY>YOURAUTHKEY</AUTHKEY>
            <ROUTE>default</ROUTE>
            <SMS TEXT='message1 testing' FROM='AAAAAA'>
            <ADDRESS TO='9999999990'></ADDRESS>
            </SMS>
            </MESSAGE>"
        String output = Functions.hitUrl(strUrl, xmlData);
        System.out.println("Output is: "+output);
    }
}
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        
      
            <?php
        }
        
        
        ######################
        
        
        
        
        
        
        
        // Java With jsp
        if (isset($page_type) && $page_type && $page_type == 10) {
            ?>
<div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">jsp Sample Code For Send Text SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="java">
<% //Import java Packages for executing Script --%>
<%@page import="java.io.PrintWriter"%>
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>
<%@ page import="java.net.HttpURLConnection"%>
<%@ page import="java.net.URL"%>
<%@ page import="java.net.URLEncoder"%>
<%
       String messageSuccess = "error";
try {
        //Your authentication key
        String authkey = "YourAuthKey";
        //Multiple mobiles numbers separated by comma
        String mobiles = "MobileNumber";
        //Your message to send, Add URL encoding here.
        String message = "Hello User. Have you a great Day.";
        //Sender ID, sender id should be 6 characters long /Use Capital Alphabet.
        String sender = "SenderId";
        //Define route 
        String route = "default";
        //Prepare you post parameters
        //API URL 
        String requestUrl = "http://sms.bulksmsserviceproviders.com/api/send_http.php?"
                        + "authkey="
                        + URLEncoder.encode(authkey, "UTF-8")
                        + "&mobiles="
                        + URLEncoder.encode(mobiles, "UTF-8")
                        + "&message="
                        + URLEncoder.encode(message, "UTF-8")
                        + "&sender="
                        + URLEncoder.encode(sender, "UTF-8")
                        + "&route=" + URLEncoder.encode(route, "UTF-8");
        URL url = new URL(requestUrl);
        HttpURLConnection uc = (HttpURLConnection) url.openConnection();
                messageSuccess = uc.getResponseMessage();
                uc.disconnect();
        } catch (Exception ex) {
                System.out.println(ex.getMessage());
        }
%>
    </code>
            </pre>
        </div>
    </div>
</div> 
</div>
</div>
    <?php
    }
#######################
        
        
        // Python
        if (isset($page_type) && $page_type && $page_type == 5) {
            ?>
<div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">Python Sample Code For Send Text SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="python">
import urllib # Python URL functions
import urllib2 # Python URL functions
authkey = "YourAuthKey" # Your authentication key.
mobiles = "9999999999" # Multiple mobiles numbers separated by comma.
message = "Test message" # Your message to send.
sender = "112233" # Sender ID,While using route4 sender id should be 6 characters long.
route = "default" # Define route
# Prepare you post parameters
values = {
    'authkey' : authkey,
    'mobiles' : mobiles,
    'message' : message,
    'sender' : sender,
    'route' : route
}
url = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_http.php" # API URL
postdata = urllib.urlencode(values) # URL encoding the data here.
req = urllib2.Request(url, postdata)
response = urllib2.urlopen(req)
output = response.read() # Get Response
print output # Print Response
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        <div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">Python Sample Code For Send Voice SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="python">
import urllib # Python URL functions
import urllib2 # Python URL functions
authkey = "YourAuthKey" # Your authentication key.
mobiles = "9999999999" # Multiple mobiles numbers separated by comma.
message = "Voice File Url" # Your message to send.
sender = "9999999999" # Sender , your source number
route = "default" # Define route
duration = "30" #Duration in seconds
# Prepare you post parameters
values = {
    'authkey' : authkey,
    'mobiles' : mobiles,
    'message' : message,
    'sender' : sender,
    'route' : route,
    'duration' : duration
}
url = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php" # API URL
postdata = urllib.urlencode(values) # URL encoding the data here.
req = urllib2.Request(url, postdata)
response = urllib2.urlopen(req)
output = response.read() # Get Response
print output # Print Response
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        
            <?php
        }

        // Windows 8
        if (isset($page_type) && $page_type && $page_type == 6) {
            ?>
        
           <div class="row">
        <div class="col-md-7 borderR">
            <div class="portlet">
                <div class="mw-parameter">
                    <h2 class="content-header-title">Windows 8 Sample Code For Send Text SMS</h2>
                    <div class="portlet-content">
                        <pre>
<code data-language="csharp">
try
{
    string strResult = "";
    //Prepare you post parameters
    var postValues = new List&lt;KeyValuePair&lt;string, string &gt; &gt;();
    //Your authentication key
    postValues.Add(new KeyValuePair&lt;string, string &gt;("authkey", "YourAuthKey"));
    //Multiple mobiles numbers separated by comma
    postValues.Add(new KeyValuePair&lt;string, string &gt;("mobiles", "9999999"));
    //Sender ID,While using route4 sender id should be 6 characters long.
    postValues.Add(new KeyValuePair&lt;string, string &gt;("sender", "102234"));
    //Your message to send, Add URL encoding here.
    string message = HttpUtility.UrlEncode("Test message");
    postValues.Add(new KeyValuePair&lt;string, string &gt;("message", message));
    //Select route
    postValues.Add(new KeyValuePair&lt;string, string &gt;("route","default"));
    //Prepare API to send SMS
    Uri requesturl = new Uri("<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_http.php");
    //create httpclient request
    var httpClient = new HttpClient();
    var httpContent = new HttpRequestMessage(HttpMethod.Post, requesturl);
    httpContent.Headers.ExpectContinue = false;
    httpContent.Content = new FormUrlEncodedContent(postValues);
    HttpResponseMessage response = await httpClient.SendAsync(httpContent);
    //Get response
    var result = await response.Content.ReadAsStringAsync();
    strResult = result.ToString();
    response.Dispose();
    httpClient.Dispose();
    httpContent.Dispose();
}
catch (Exception ex)
{
    throw ex;
}
</code>
                        </pre>
                    </div>
                </div>
            </div> 
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-7 borderR">
            <div class="portlet">
                <div class="mw-parameter">
                    <h2 class="content-header-title">Windows 8 Sample Code For Send Voice SMS</h2>
                    <div class="portlet-content">
                        <pre>
<code data-language="csharp">
try
{
    string strResult = "";
    //Prepare you post parameters
    var postValues = new List&lt;KeyValuePair&lt;string, string &gt; &gt;();
    //Your authentication key
    postValues.Add(new KeyValuePair&lt;string, string &gt;("authkey", "YourAuthKey"));
    //Multiple mobiles numbers separated by comma
    postValues.Add(new KeyValuePair&lt;string, string &gt;("mobiles", "9999999"));
    //Sender ID,While using route4 sender id should be 6 characters long.
    postValues.Add(new KeyValuePair&lt;string, string &gt;("sender", "9999999999"));
    //Your message to send, Add URL encoding here.
    string message = HttpUtility.UrlEncode("Voice File Url");
    postValues.Add(new KeyValuePair&lt;string, string &gt;("message", message));
    //Select route
    postValues.Add(new KeyValuePair&lt;string, string &gt;("route","default"));
    //Duration in seconds
    postValues.Add(new KeyValuePair&lt;string, string &gt;("duration","30"));
    //Prepare API to send SMS
    Uri requesturl = new Uri("<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php");
    //create httpclient request
    var httpClient = new HttpClient();
    var httpContent = new HttpRequestMessage(HttpMethod.Post, requesturl);
    httpContent.Headers.ExpectContinue = false;
    httpContent.Content = new FormUrlEncodedContent(postValues);
    HttpResponseMessage response = await httpClient.SendAsync(httpContent);
    //Get response
    var result = await response.Content.ReadAsStringAsync();
    strResult = result.ToString();
    response.Dispose();
    httpClient.Dispose();
    httpContent.Dispose();
}
catch (Exception ex)
{
    throw ex;
}
</code>
                        </pre>
                    </div>
                </div>
            </div> 
        </div>
    </div>
        
     
        <?php
        }

        // Android
        if (isset($page_type) && $page_type && $page_type == 7) {
                                                                    ?>
<div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">Android Sample Code For Send Text SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="java">
//Your authentication key
String authkey = "YourAuthKey";
//Multiple mobiles numbers separated by comma
String mobiles = "9999999";
//Sender ID,While using route4 sender id should be 6 characters long.
String senderId = "102234";
//Your message to send, Add URL encoding here.
String message = "Test message";
//define route
String route="default";
URLConnection myURLConnection=null;
URL myURL=null;
BufferedReader reader=null;
//encoding message 
String encoded_message=URLEncoder.encode(message);
//Send SMS API
String mainUrl="<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_http.php";
//Prepare parameter string 
StringBuilder sbPostData= new StringBuilder(mainUrl);
sbPostData.append("authkey="+authkey); 
sbPostData.append("&mobiles="+mobiles);
sbPostData.append("&message="+encoded_message);
sbPostData.append("&route="+route);
sbPostData.append("&sender="+senderId);
//final string
mainUrl = sbPostData.toString();
try
{
    //prepare connection
    myURL = new URL(mainUrl);
    myURLConnection = myURL.openConnection();
    myURLConnection.connect();
    reader= new BufferedReader(new InputStreamReader(myURLConnection.getInputStream()));
    //reading response 
    String response;
    while ((response = reader.readLine()) != null) 
    //print response 
    Log.d("RESPONSE", ""+response);
    //finally close connection
    reader.close();
} 
catch (IOException e) 
{ 
    e.printStackTrace();
} 
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        
        <div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">Android Sample Code For Send Voice SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="java">
//Your authentication key
String authkey = "YourAuthKey";
//Multiple mobiles numbers separated by comma
String mobiles = "9999999";
//Sender ID,While using route4 sender id should be 6 characters long.
String sender = "9999999999";
//Your message to send, Add URL encoding here.
String message = "Voice File Url";
//define route
String route="default";
//Duration in seconds
String duration="30";
URLConnection myURLConnection=null;
URL myURL=null;
BufferedReader reader=null;
//encoding message 
String encoded_message=URLEncoder.encode(message);
//Send SMS API
String mainUrl="<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php";
//Prepare parameter string 
StringBuilder sbPostData= new StringBuilder(mainUrl);
sbPostData.append("authkey="+authkey); 
sbPostData.append("&mobiles="+mobiles);
sbPostData.append("&message="+encoded_message);
sbPostData.append("&route="+route);
sbPostData.append("&sender="+sender);
sbPostData.append("&sender="+duration);
//final string
mainUrl = sbPostData.toString();
try
{
    //prepare connection
    myURL = new URL(mainUrl);
    myURLConnection = myURL.openConnection();
    myURLConnection.connect();
    reader= new BufferedReader(new InputStreamReader(myURLConnection.getInputStream()));
    //reading response 
    String response;
    while ((response = reader.readLine()) != null) 
    //print response 
    Log.d("RESPONSE", ""+response);
    //finally close connection
    reader.close();
} 
catch (IOException e) 
{ 
    e.printStackTrace();
} 
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
                                                                    <?php
                                                                }

        // IOS
        if (isset($page_type) && $page_type && $page_type == 8) {
                                                                    ?>
<div class="row">
    <div class="col-md-8 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">iOS Sample Code For Send Text SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="c">
//Create Objects
NSMutableData * responseData;
NSURLConnection * connection;

// In your viewDidLoad method add this lines
-(void)viewDidLoad
{
    [super viewDidLoad]; 
    //Your authentication key
    NSString * authkey = @"YourAuthKey";
    //Multiple mobiles numbers separated by comma
    NSString * mobiles = @"9999999"; 
    //Sender ID,While using route4 sender id should be 6 characters long.
    NSString * senderId = @"102234";
    //Your message to send, Add URL encoding here.
    NSString * message = @"Test message";
    //define route
    NSString * route = @"default";
    // Prepare your url to send sms with this parameters.
    NSString * url = [[NSString stringWithFormat:@"<?php echo str_replace("http://", "", (isset($domain_name) && $domain_name) ? $domain_name : ""); ?>api/send_http.php?authkey=%@&mobiles=%@&message=%@&sender=%@&route=%@", authkey, mobiles, message, senderId, route] stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSURLRequest * request = [NSURLRequest requestWithURL:[NSURL URLWithString:url]];
    connection = [[NSURLConnection alloc] initWithRequest:request delegate:self];
}

// implement URLConnection Delegate Methods as follow
-(void) connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response
{
    //Get response data
    responseData = [NSMutableData data];
} 
-(void) connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{
    [responseData appendData:data];
}
-(void) connection:(NSURLConnection *)connection didFailWithError:(NSError *)error
{
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Error" message:error.localizedDescription delegate:self cancelButtonTitle:nil otherButtonTitles:@"OK", nil];
    [alert show];
}
-(void) connectionDidFinishLoading:(NSURLConnection *)connection
{
    // Get response data in NSString.
    NSString * responceStr = [[NSString alloc] initWithData:responseData encoding:NSUTF8StringEncoding];
}
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        
        <div class="row">
    <div class="col-md-8 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">iOS Sample Code For Send Voice SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="c">
//Create Objects
NSMutableData * responseData;
NSURLConnection * connection;

// In your viewDidLoad method add this lines
-(void)viewDidLoad
{
    [super viewDidLoad]; 
    //Your authentication key
    NSString * authkey = @"YourAuthKey";
    //Multiple mobiles numbers separated by comma
    NSString * mobiles = @"9999999"; 
    //Sender ID,While using route4 sender id should be 6 characters long.
    NSString * sender = @"9999999999";
    //Your message to send, Add URL encoding here.
    NSString * message = @" Voice File Url";
    //define route
    NSString * route = @"default";
     //Duration in seconds
    NSString * duration = @"30";
    // Prepare your url to send sms with this parameters.
    NSString * url = [[NSString stringWithFormat:@"<?php echo str_replace("http://", "", (isset($domain_name) && $domain_name) ? $domain_name : ""); ?>api/send_voice_http.php?authkey=%@&mobiles=%@&message=%@&sender=%@&route=%@", authkey, mobiles, message, sender, route,duration] stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSURLRequest * request = [NSURLRequest requestWithURL:[NSURL URLWithString:url]];
    connection = [[NSURLConnection alloc] initWithRequest:request delegate:self];
}

// implement URLConnection Delegate Methods as follow
-(void) connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response
{
    //Get response data
    responseData = [NSMutableData data];
} 
-(void) connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{
    [responseData appendData:data];
}
-(void) connection:(NSURLConnection *)connection didFailWithError:(NSError *)error
{
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Error" message:error.localizedDescription delegate:self cancelButtonTitle:nil otherButtonTitles:@"OK", nil];
    [alert show];
}
-(void) connectionDidFinishLoading:(NSURLConnection *)connection
{
    // Get response data in NSString.
    NSString * responceStr = [[NSString alloc] initWithData:responseData encoding:NSUTF8StringEncoding];
}
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
                                                                    <?php
                                                                }

        // VB6
        if (isset($page_type) && $page_type && $page_type == 9) {
                                                                    ?>
<div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">VB6 Sample Code For Send Text SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="csharp">
Private Sub Command1_Click()
Dim DataToSend As String
Dim objXML As Object
Dim message As String
Dim authKey As String
Dim mobiles As String
Dim sender As String
Dim route As String
Dim URL As String
'Set these variables
authKey = "Your auth key";
mobiles  = "9999999999";
'Sender ID,While using route4 sender id should be 6 characters long.
sender = "TESTIN"; 
' this url encode function may not work fully functional.
message = URLEncode(" Your message ")
'Define route
route = "default" 
' do not use https 
URL = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_http.php" 
Set objXML = CreateObject("Microsoft.XMLHTTP")
objXML.Open "POST", URL , False
objXML.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
objXML.send "authkey=" + authKey + "&mobiles=" + mobiles + "&message=" + message + "&sender=" + sender + "&route=" + route
If Len(objXML.responseText) > 0 Then
MsgBox objXML.responseText
End If
End Sub
Function URLEncode(ByVal Text As String) As String
Dim i As Integer
Dim acode As Integer
Dim char As String
URLEncode = Text
For i = Len(URLEncode) To 1 Step -1
acode = Asc(Mid$(URLEncode, i, 1))
Select Case acode
Case 48 To 57, 65 To 90, 97 To 122
' don't touch alphanumeric chars
Case 32
' replace space with "+"
Mid$(URLEncode, i, 1) = "+"
Case Else
' replace punctuation chars with "%hex"
URLEncode = Left$(URLEncode, i - 1) & "%" & Hex$(acode) & Mid$ _
(URLEncode, i + 1)
End Select
Next
End Function
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
        
        <div class="row">
    <div class="col-md-7 borderR">
        <div class="portlet">
            <div class="mw-parameter">
                <h2 class="content-header-title">VB6 Sample Code For Send Voice SMS</h2>
                <div class="portlet-content">
                    <pre>
<code data-language="csharp">
Private Sub Command1_Click()
Dim DataToSend As String
Dim objXML As Object
Dim message As String
Dim authKey As String
Dim mobiles As String
Dim sender As String
Dim route As String
Dim duration As String
Dim URL As String
'Set these variables
authKey = "Your auth key";
mobiles  = "9999999999";
'Sender , source mobile number
sender = "9999999999"; 
' this url encode function may not work fully functional.
message = URLEncode(" Voice File Url ")
'Define route
route = "default" 
duration = "30" 
' do not use https 
URL = "<?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php" 
Set objXML = CreateObject("Microsoft.XMLHTTP")
objXML.Open "POST", URL , False
objXML.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
objXML.send "authkey=" + authKey + "&mobiles=" + mobiles + "&message=" + message + "&sender=" + sender + "&route=" + route + "&duration=" + duration
If Len(objXML.responseText) > 0 Then
MsgBox objXML.responseText
End If
End Sub
Function URLEncode(ByVal Text As String) As String
Dim i As Integer
Dim acode As Integer
Dim char As String
URLEncode = Text
For i = Len(URLEncode) To 1 Step -1
acode = Asc(Mid$(URLEncode, i, 1))
Select Case acode
Case 48 To 57, 65 To 90, 97 To 122
' don't touch alphanumeric chars
Case 32
' replace space with "+"
Mid$(URLEncode, i, 1) = "+"
Case Else
' replace punctuation chars with "%hex"
URLEncode = Left$(URLEncode, i - 1) & "%" & Hex$(acode) & Mid$ _
(URLEncode, i + 1)
End Select
Next
End Function
</code>
                    </pre>
                </div>
            </div>
        </div> 
    </div>
</div>
                                                                    <?php
                                                                }
        ?>
     </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/highlight/js/rainbow.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/highlight/js/language/generic.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/highlight/js/language/php.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/highlight/js/language/c.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/highlight/js/language/java.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/highlight/js/language/python.js"></script>
