<html>
<?php
include('../../Conf/init.php');
if(isset($_POST)==true && empty($_POST)==false){ 

$question = $_POST['question'];
$opt1 = $_POST['opt1'];
$opt2 = $_POST['opt2'];
$opt3 = $_POST['opt3'];
$opt4 = $_POST['opt4'];
$answer = $_POST['answer'];
$arrlength=count($question);
for($x=0;$x<$arrlength;$x++){
	$sql[$x]="INSERT INTO quiz (ModuleID, QuizQuestion, Opt1, Opt2, Opt3, Opt4, Answer) VALUES ('21','$question[$x]','$opt1[$x]','$opt2[$x]','$opt3[$x]','$opt4[$x]','$answer[$x]')";
	if(!mysqli_query($conn,$sql[$x]))
		{
			die('Error:' .mysqli_error($conn));
		}
}
}			

	
?>
<head>
    <link rel="stylesheet" type="text/css" href="../../CSS/quiz.css" >          
</head>
<body>
<form action="" method="post">
	<div id="dynamicInput">
        1. Question:<br />
        <textarea name="question[]" required="required" rows="3" cols="40"></textarea><br />
        Opt1 <input type="text" name="opt1[]" /><br />
        Opt2 <input type="text" name="opt2[]" /><br />
        Opt3 <input type="text" name="opt3[]" /><br />
        Opt4 <input type="text" name="opt4[]" /><br />
        Ans 
        <select name="answer[]" required="required">
            <option value="">Please select</option>
            <option value="opt1">Opt1</option>
            <option value="opt2">Opt2</option>
            <option value="opt3">Opt3</option>
            <option value="opt4">Opt4</option>
        </select><br />
	</div>
        <input type="button" value="+ Add a question" onClick="addInput('dynamicInput');"><br />
        <input type="submit" name="submit" />
    </form>
	
	<script>
	    var counter = 1;
		var limit = 10;

		function addInput(divName){
        if (counter == limit)  {
            alert("You have reached the limit of adding " + counter + " questions");
         } else {
            var newdiv = document.createElement('div');
            newdiv.innerHTML = (counter + 1) + 
			'. Question:' + 
			' <br><textarea name="question[]" required rows="3" cols="40"></textarea><br />' + 
			'Opt1 <input type="text" name="opt1[]" /><br />' + 
			'Opt2 <input type="text" name="opt2[]" /><br />' + 
			'Opt3 <input type="text" name="opt3[]" /><br />' + 
			'Opt4 <input type="text" name="opt4[]" /><br />' + 
			'Ans <select name="answer[]" required >' +
            '<option value="" >Please select</option>' +
            '<option value="opt1">Opt1</option>' +
            '<option value="opt2">Opt2</option>' +
            '<option value="opt3">Opt3</option>' +
            '<option value="opt4">Opt4</option>' +
        '</select><br />';
            document.getElementById(divName).appendChild(newdiv);
            counter++;
         }
		}
	</script>
	
</body>
</html>