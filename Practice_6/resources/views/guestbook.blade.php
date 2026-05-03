<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Guestbook - Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card border-primary mb-4 shadow-sm">
        <div class="card-header bg-primary text-white fs-5">
            Guestbook form
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">

                    @if(session('infoMessage'))
                        <div class="alert alert-danger">
                            {{ session('infoMessage') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('guestbook.store') }}" class="fw-bold">
                        @csrf

                        <div class="mb-3">
                            <label for="exampleInputEmail" class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail" placeholder="Enter email" required>
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputName" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Enter name" required>
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputText" class="form-label">Text</label>
                            <textarea name="text" class="form-control" id="exampleInputText" placeholder="Enter text" rows="3" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="card border-secondary shadow-sm mb-5">
        <div class="card-header bg-secondary text-white fs-5">
            Comments
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">

                    @foreach($comments as $comment)
                        <div class="mb-3">
                            <h6 class="mb-1 fw-bold">{{ $comment->name }} <span class="text-muted fw-normal">({{ $comment->email }})</span></h6>
                            <p class="mb-1">{{ $comment->text }}</p>
                            <small class="text-muted">{{ $comment->date }}</small>
                        </div>
                        <hr>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
