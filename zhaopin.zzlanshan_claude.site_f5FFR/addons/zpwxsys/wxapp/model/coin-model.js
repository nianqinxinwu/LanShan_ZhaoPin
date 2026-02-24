import {Base} from '../utils/base.js';

class Coin extends Base{
    constructor(){
        super();
    }

    getBalance(callback, params){
        var param={
            url: 'v1.Coin/getBalance',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    recharge(callback, params){
        var param={
            url: 'v1.Coin/recharge',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getCoinRecords(callback, params){
        var param={
            url: 'v1.Coin/getCoinRecords',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getPointRecords(callback, params){
        var param={
            url: 'v1.Coin/getPointRecords',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    consume(callback, params){
        var param={
            url: 'v1.Coin/consume',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    exchangePoints(callback, params){
        var param={
            url: 'v1.Coin/exchangePoints',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getRechargeOptions(callback, params){
        var param={
            url: 'v1.Coin/getRechargeOptions',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    checkServiceStatus(callback, params){
        var param={
            url: 'v1.Coin/checkServiceStatus',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

};

export {Coin};
