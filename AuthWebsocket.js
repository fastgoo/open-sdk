/**
 * websocket二维码扫码授权
 * @type {{server: string, pingTaskId: null, app_id: null, tokenCall: null, qrcodeCall: null, getKey: authWebsocket.getKey, setAppId: authWebsocket.setAppId, connectCallback: authWebsocket.connectCallback, closeCallback: authWebsocket.closeCallback, setQrcodeCallback: authWebsocket.setQrcodeCallback, setTokenCallback: authWebsocket.setTokenCallback, messageCallback: authWebsocket.messageCallback, sendMsg: authWebsocket.sendMsg, getQrcode: authWebsocket.getQrcode}}
 */
var authWebsocket = {
    /**
     * websocket服务地址
     */
    server: "wss://wss.fastgoo.net/wechat",
    /**
     * 心跳taskId
     */
    pingTaskId: null,
    /**
     * app_id，数据校验加密
     */
    app_id: null,
    /**
     * 服务端推动token回调
     */
    tokenCall: null,
    /**
     * 服务端推动二维码回调
     */
    qrcodeCall: null,
    /**
     * 获取随机数字
     * @returns {number}
     */
    getKey: function () {
        return parseInt(Math.random() * (999999 - 100000 + 1) + 100000)
    },
    /**
     * 设置app_id参数
     * @param app_id
     */
    setAppId: function (app_id) {
        this.app_id = app_id;
    },
    /**
     * socket连接成功回调方法
     * @param ret
     */
    connectCallback: function (ret) {
        var that = this;
        this.pingTaskId = setInterval(function () {
            that.sendMsg('ping', {});
        }, 30000);
    },
    /**
     * socket关闭回调方法
     * @param ret
     */
    closeCallback: function (ret) {
        clearInterval(this.pingTaskId);
    },
    /**
     * 设置二维码回调处理逻辑
     * @param call
     */
    setQrcodeCallback: function (call) {
        this.qrcodeCall = call;
    },
    /**
     * 设置token回调处理逻辑
     * @param call
     */
    setTokenCallback: function (call) {
        this.tokenCall = call;
    },
    /**
     * socket收到消息回调处理方法
     * @param ret
     */
    messageCallback: function (ret) {
        if (ret.head.type == 'getQrcode') {
            this.qrcodeCall(ret);
            this.messageCallback = function (ret) {
                if (ret.head.type == 'getQrcodeAuthInfo') {
                    this.tokenCall(ret);
                }
            }
        }
    },
    /**
     * 发送消息（后面会重置）
     * @param type
     * @param data
     */
    sendMsg: function (type, data) {

    },
    /**
     * 获取二维码(发送数据)
     */
    getQrcode: function () {
        this.sendMsg('getQrcode', {key: this.getKey(), app_id: this.app_id});
    }
}
/**
 * websocket初始化
 * @type {WebSocket}
 */
var socket = new WebSocket(authWebsocket.server);

/**
 * 连接
 * @param evt
 */
socket.onopen = function (evt) {
    console.log('连接服务器成功');
    socket.send("成功连接到服务器");
    authWebsocket.connectCallback(evt.data);
};
/**
 * 重置 authWebsocket 发送消息方法
 * @param type
 * @param data
 */
authWebsocket.sendMsg = function (type, data) {
    var ret = {
        head: {
            type: type,
            token: ''
        },
        body: data
    }
    socket.send(JSON.stringify(ret));
}
/**
 * socket断开事件
 * @param evt
 */
socket.onclose = function (evt) {
    authWebsocket.closeCallback(evt);
};
/**
 * socket收到消息事件
 * @param evt
 */
socket.onmessage = function (evt) {
    authWebsocket.messageCallback(JSON.parse(evt.data));
};