<!DOCTYPE html>
<html lang="en">
<head>
  <title>Reset Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Reset Password</h2>
  <form action="{{url('reset_password').'/'.$password_token}}" method="POST">
    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
    <input name="_id" type="hidden" value="{{$id}}">
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
      @if(isset($errors) && $errors->has('password'))
        <span class="error-message">{!!$errors->first('password')!!}</span>
      @endif
    </div>
    <div class="form-group">
      <label for="pwd">Confirm Password:</label>
      <input type="password" class="form-control" id="password_confirmation" placeholder="Enter Confirm password" name="password_confirmation">
      @if(isset($errors) && $errors->has('password_confirmation'))
        <span class="error-message">{!!$errors->first('password_confirmation')!!}</span>
      @endif
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
  </form>
</div>

</body>
</html>
