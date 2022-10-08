{{-- // FirebaseUI のログイン ウィジェットがレンダリングされる HTML 要素を定義します。 --}}
    <h1>Welcome to My Awesome App</h1>
    <div id="firebaseui-auth-container"></div>
    <div id="loader">Loading...</div>

<script>
    // SDK をインポートした後、Auth UI を初期化します。
    var ui = new firebaseui.auth.AuthUI(firebase.auth());

    /*
    *メールアドレスとパスワード
    */
    // FirebaseUI signInOptions のリストにメール プロバイダ ID を追加します。
    ui.start('#firebaseui-auth-container', {
        signInOptions: [
            firebase.auth.EmailAuthProvider.PROVIDER_ID
        ],
        // Other config options...
    });

    /*
    *メールリンク認証
    */
    // FirebaseUI signInOptions のリストにメールプロバイダ ID とメールリンク signInMethod を追加します。 
    ui.start('#firebaseui-auth-container', {
        signInOptions: [
            {
            provider: firebase.auth.EmailAuthProvider.PROVIDER_ID,
            signInMethod: firebase.auth.EmailAuthProvider.EMAIL_LINK_SIGN_IN_METHOD
            }
        ],
        // Other config options...
    });

    // Is there an email link sign-in?
    if (ui.isPendingRedirect()) {
        ui.start('#firebaseui-auth-container', uiConfig);
    }
    // This can also be done via:
    if (firebase.auth().isSignInWithEmailLink(window.location.href)) {
        ui.start('#firebaseui-auth-container', uiConfig);
    }

    /*
    *OAuth プロバイダ（Google、Facebook、Twitter、GitHub）
    */
    //FirebaseUI signInOptions のリストに OAuth プロバイダ ID を追加します。
    ui.start('#firebaseui-auth-container', {
        signInOptions: [
            // List of OAuth providers supported.
            firebase.auth.GoogleAuthProvider.PROVIDER_ID,
            firebase.auth.FacebookAuthProvider.PROVIDER_ID,
            firebase.auth.TwitterAuthProvider.PROVIDER_ID,
            firebase.auth.GithubAuthProvider.PROVIDER_ID
        ],
        // Other config options...
    });

    /*
    * ログイン
    */
    // FirebaseUI のログインフローを開始するには、基盤となる Auth インスタンスを渡して FirebaseUI インスタンスを初期化します。
    var ui = new firebaseui.auth.AuthUI(firebase.auth());

    // FirebaseUI の設定を指定します（サポートするプロバイダ、UI のカスタマイズ、成功時のコールバックなど）。
    var uiConfig = {

        callbacks: {
            signInSuccessWithAuthResult: function(authResult, redirectUrl) {
            // User successfully signed in.
            // Return type determines whether we continue the redirect automatically
            // or whether we leave that to developer to handle.
            return true;
            },
            uiShown: function() {
            // The widget is rendered.
            // Hide the loader.
            document.getElementById('loader').style.display = 'none';
            }
        },

        // Will use popup for IDP Providers sign-in flow instead of the default, redirect.
        signInFlow: 'popup',
        signInSuccessUrl: '<url-to-redirect-to-on-success>',

        signInOptions: [
            // Leave the lines as is for the providers you want to offer your users.
            firebase.auth.GoogleAuthProvider.PROVIDER_ID,
            firebase.auth.FacebookAuthProvider.PROVIDER_ID,
            firebase.auth.TwitterAuthProvider.PROVIDER_ID,
            firebase.auth.GithubAuthProvider.PROVIDER_ID,
            firebase.auth.EmailAuthProvider.PROVIDER_ID,
            firebase.auth.PhoneAuthProvider.PROVIDER_ID
        ],

        // Terms of service url.
        tosUrl: '<your-tos-url>',

        // Privacy policy url.
        privacyPolicyUrl: '<your-privacy-policy-url>'
    };

    // 最後に、FirebaseUI Auth のインターフェースをレンダリングします。
    // The start method will wait until the DOM is loaded.
    ui.start('#firebaseui-auth-container', uiConfig);
</script>