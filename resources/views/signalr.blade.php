{{-- Layout --}}
@extends('app')

{{-- Title --}}
@section('title', 'SignalR')

{{-- Body --}}
@section('content')

<section class="section">
    <div class="section-header" style="display: inherit;">
        <h1>SignalR Tester</h1>
        <br>
        <small>You might wanna open 2 pages with the same Connection to see the Websocket result</small>
        <a href="" class="btn btn-primary" style="float: right">Pusher</a>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="" method="get">
                        <div class="card-header">
                            <h4>Connection</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" name="url" id="url" class="form-control" placeholder="url" value="{{ request('url', 'https://localhost:44336') }}">
                            </div>
                            <div class="form-group">
                                <label for="token">Token</label>
                                <input type="text" name="token" id="token" class="form-control" placeholder="token" value="{{ request('token') }}">
                            </div>
                            {{-- Submit --}}
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Connect</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Message</h4>
                    </div>
                    <div class="card-body">
                        <small></small>
                        
                        <div class="form-row">
                            <label for="data">Responses</label><br>
                            <textarea class="form-control" id="data" rows="6" style="height: unset" hidden></textarea>
                        </div>

                        <input type="text" class="form-control" id="messageInput" />
                        <button class="btn btn-primary" onclick="sendMessage()">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

{{-- Style with stack --}}
@push('styles')

<script src="https://cdnjs.cloudflare.com/ajax/libs/microsoft-signalr/8.0.7/signalr.min.js" integrity="sha512-7SRCYIJtR6F8ocwW7UxW6wGKqbSyqREDbfCORCbGLatU0iugBLwyOXpzhkPyHIFdBO0K2VCu57fvP2Twgx1o2A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endpush

{{-- Scripts --}}
@push('scripts')

<script>
    // Get token from query string
    var token = new URLSearchParams(window.location.search).get("token");
    var url = new URLSearchParams(window.location.search).get("url");
    var data = parseJwt(token);

    console.log(data);
    const connection = new signalR.HubConnectionBuilder()
        .withUrl((url) ?? 'https://localhost:44336/ws', {
            accessTokenFactory: () => token,
            transport: signalR.HttpTransportType.WebSockets
        })
        .configureLogging(signalR.LogLevel.Debug)
        .build();

    connection.on("Notifications", (data) => {
        appendToEditor(JSON.parse(data));
    });

    connection.start().then(() => {
        console.log("Connected to SignalR hub");
    }).catch((err) => {
        console.error("Error connecting to SignalR hub:", err);
    });

    function sendMessage() {
        const user = "User";
        const message = document.getElementById("messageInput").value;

        connection.invoke("SendMessage", user, message);
    }
    
    function joinGroup() {
        connection.invoke("JoinGroup", data.Role);
    }

    function parseJwt(jwt) {
        return JSON.parse(
            decodeURIComponent(
                Array.prototype.map.call(
                    atob(jwt.split('.')[1].replace('-', '+').replace('_', '/')),
                    (c) => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
                ).join('')
            )
        );
    }

    var editor

    document.addEventListener('DOMContentLoaded', function() {
        textarea = document.getElementById('data');
        editor = window.CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
            mode: 'javascript',
            json: true,
            theme: 'duotone-dark',
            readOnly: true,
            scrollbarStyle: 'null',
        });
    });
    
    function appendToEditor(params) {
        editor.setValue(editor.getValue() + '\n' + JSON.stringify(params, null, 2));
    }
</script>

@endpush

<!DOCTYPE html>
<html>
<head>
</head>
<body>
</body>
</html>