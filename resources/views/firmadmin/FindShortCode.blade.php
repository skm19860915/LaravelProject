<div class="progress">
    <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div><br>
<form method="post" action="{{url('FindShortCodeDataSave')}}"> 
    @csrf
    <input type="hidden" name="returnpage" value="{{$rUrl}}">
    <input type="hidden" name="userID" value="{{$ID}}">
    <?php
    $A['ShortCode']=$ShortCode;
    $A['title']='';
    $A['UserID']=$ID;
    $A['CaseID']=$CID;
    
    ?>
    <div class="FindShortCodeDataSaveBox"><?php CallPDFDataBYGroup($A); ?></div>
    <div class="SaveInfo"><input class="btn btn-success" type="submit" value="Save All Information"></div>
     
</form>
