import { Company } from '../../model/company-model.js';
import { Coin } from '../../model/coin-model.js';

var company = new Company();
var coin = new Coin();

Page({

  data: {
    jobid: '',
    jobinfo: {},
    selectedType: '',
    accountInfo: null,
    coinBalance: 0,
    spreadCount: 0,
    groupCreated: false,
    signinPurchased: false
  },

  onLoad: function (options) {
    var that = this;

    if (options.jobid) {
      that.setData({ jobid: options.jobid });
    }

    that.loadJobInfo();
    that.loadAccountInfo();
    that.loadCoinBalance();
    that.loadServiceStatus();
  },

  loadJobInfo: function () {
    var that = this;
    var ctoken = wx.getStorageSync('ctoken');
    var params = { ctoken: ctoken, id: that.data.jobid };

    company.companyjob((data) => {
      if (data.joblist && data.joblist.length > 0) {
        var job = null;
        for (var i = 0; i < data.joblist.length; i++) {
          if (data.joblist[i].id == that.data.jobid) {
            job = data.joblist[i];
            break;
          }
        }
        if (job) {
          that.setData({ jobinfo: job });
        }
      }
    }, params);
  },

  loadAccountInfo: function () {
    var that = this;
    var ctoken = wx.getStorageSync('ctoken');
    var params = { ctoken: ctoken };

    company.companycenter((data) => {
      that.setData({
        accountInfo: data
      });
    }, params);
  },

  loadCoinBalance: function () {
    var that = this;
    coin.getBalance(function (data) {
      if (data.status == 0) {
        that.setData({ coinBalance: data.coin_balance || 0 });
      }
    }, {});
  },

  selectService: function (e) {
    var type = e.currentTarget.dataset.type;
    // 已开通的服务不可再次选择
    if (type == 'create_group' && this.data.groupCreated) {
      wx.showToast({ title: '群聊已创建，无需重复购买', icon: 'none' });
      return;
    }
    if (type == 'signin_service' && this.data.signinPurchased) {
      wx.showToast({ title: '签到服务已开通，无需重复购买', icon: 'none' });
      return;
    }
    this.setData({ selectedType: type, spreadCount: 0 });
  },

  selectSpreadOption: function (e) {
    var count = parseInt(e.currentTarget.dataset.count);
    this.setData({ spreadCount: count });
  },

  loadServiceStatus: function () {
    var that = this;
    if (!that.data.jobid) return;
    coin.checkServiceStatus(function (data) {
      if (data.status == 0) {
        that.setData({
          groupCreated: !!data.group_created,
          signinPurchased: !!data.signin_purchased
        });
      }
    }, { related_id: that.data.jobid });
  },

  doBuyService: function () {
    var that = this;

    if (!that.data.selectedType) {
      wx.showToast({ title: '请选择服务类型', icon: 'none', duration: 2000 });
      return;
    }

    var companyid = wx.getStorageSync('companyid');
    var params = { companyid: companyid, id: that.data.jobid };

    if (that.data.selectedType == 'top') {
      that.payCoinService(10, 'topjob', '置顶服务（10章鱼币/24h）');
    } else if (that.data.selectedType == 'spread') {
      var spreadCount = that.data.spreadCount;
      if (!spreadCount) {
        wx.showToast({ title: '请选择扩散人数', icon: 'none', duration: 2000 });
        return;
      }
      var spreadPriceMap = { 50: 5, 100: 10, 200: 20 };
      var amount = spreadPriceMap[spreadCount];
      that.payCoinService(amount, 'spreadjob', '加急扩散' + spreadCount + '人（' + amount + '章鱼币）');
    } else if (that.data.selectedType == 'refresh') {
      company.doCompanyendtime((data) => {
        wx.showToast({
          title: data.msg || '刷新成功',
          icon: 'none',
          duration: 2000
        });
        setTimeout(function () { wx.navigateBack(); }, 1500);
      }, params);
    } else if (that.data.selectedType == 'signin_service') {
      that.payCoinService(3, 'signinservice', '签到签退表服务（3章鱼币）');
    } else if (that.data.selectedType == 'create_group') {
      that.payCoinService(2, 'creategroup', '创建群聊服务（2章鱼币）');
    } else if (that.data.selectedType == 'temp_call') {
      that.payCoinService(5, 'tempcall', '临时通话服务（5章鱼币）');
    } else if (that.data.selectedType == 'custom') {
      wx.showModal({
        title: '自定义服务',
        content: '请联系客服定制专属服务方案',
        showCancel: false
      });
    }
  },

  payCoinService: function (amount, action, desc) {
    var that = this;
    if (that.data.coinBalance < amount) {
      wx.showModal({
        title: '余额不足',
        content: '章鱼币余额不足，是否前往充值？',
        success: function (res) {
          if (res.confirm) {
            wx.navigateTo({ url: '/pages/mymoney/index' });
          }
        }
      });
      return;
    }

    wx.showModal({
      title: '确认支付',
      content: desc,
      success: function (res) {
        if (res.confirm) {
          coin.consume(function (data) {
            if (data.status == 0) {
              that.setData({ coinBalance: data.balance });
              wx.showModal({
                title: '提示',
                content: '服务开通成功',
                showCancel: false,
                success: function () { wx.navigateBack(); }
              });
            } else {
              wx.showToast({ title: data.msg, icon: 'none', duration: 2000 });
            }
          }, { amount: amount, action: action, related_id: that.data.jobid });
        }
      }
    });
  },

  toRecharge: function () {
    wx.navigateTo({ url: '/pages/mymoney/index' });
  },

  onShareAppMessage: function () {
    return {
      title: '选择功能服务',
      path: '/pages/index/index'
    }
  }
})
