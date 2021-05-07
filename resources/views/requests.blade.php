@extends('layouts.base')
<title>Requests</title>
<style>
div.requestTable{
    margin:auto;
    width: 80%;
    margin-top:50px;
    
}
div.requestform{
    background-color: #333;
    margin:auto;
    width: 80%;
    
}
form{
    margin:auto;
}
select{
    /* blueish*/
    border: none;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
input.notes{
    border: 2px solid white;    
    color: white;
    background-color: #333;
    font-size: 16px;
    box-sizing: border-box;
    padding: 12px 20px;
    width: 30%;
}
table {
        border-collapse: collapse;
        border: solid 1px;
        margin: auto;
        width:100%;
}
table th{
        background-color: #333;
        border: solid 1px;
        color: white;
        
}
table td{
        border: solid 1px;
        border-color: #c9c9c9;
        padding: 5px;
}

</style>
@section('content')
<div class="requestform">
    <form>
        <select id="addDrop" name="addDrop">
            <option value="addDrop" selected disabled>Add or Drop</option>  
            <option value="add">Add</option>
            <option value="drop">Drop</option>
        </select>
        <select id="troop" name="Troop">
            <option value="test" selected disabled>Choose Troop</option>  
            <option value="au">Australia</option>
            <option value="ca">Canada</option>
            <option value="usa">USA</option>
        </select>
        <select id="scout" name="Scout">
        <option value="test" selected disabled>Choose Scout</option>  
            <option value="au">Australia</option>
            <option value="ca">Canada</option>
            <option value="usa">USA</option>
        </select>
        <select id="session" name="Session">
            <option value="test" selected disabled>Choose Session</option>  
            <option value="au">TEst</option>
            <option value="ca">Canada</option>
            <option value="usa">USA</option>
        </select>
    <input class="notes" id="notes" name="notes"placeholder="Notes">
    <button class="button" onclick="">Submit Request</button>
    </form>
</div>
<div class="requestTable">
    <h3>Approved Requests</h3>
    <table>
        <tr>
            <th>Scout</th>
            <th>Troop</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Status</th>
        </tr>
        <tr>
            <td>John Doe</td>
            <td>1234</td>
            <td>Huck Finn</td>
            <td>Monday</td>
            <td>Add</td>
            <td>Approved</td>
        </tr>
    </table>
</div>
<div class="requestTable">
    <h3>Pending Requests</h3>
    <table>
        <tr>
            <th>Scout</th>
            <th>Troop</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Status</th>
        </tr>
        <tr>
            <td>John Doe</td>
            <td>1234</td>
            <td>Huck Finn</td>
            <td>Monday</td>
            <td>Add</td>
            <td>Approved</td>
        </tr>
    </table>
</div>

@endsection