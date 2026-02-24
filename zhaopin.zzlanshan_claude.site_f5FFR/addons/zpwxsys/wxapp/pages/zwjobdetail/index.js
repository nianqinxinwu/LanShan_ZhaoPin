import { ZwJobdetail } from './zwjobdetail-model.js';
var zwjobdetail = new ZwJobdetail();
import { User } from '../../model/user-model.js';
import { Token } from '../../utils/token.js';

var token = new Token();
var user = new User();

// 时间戳转日期
function formatDate(ts) {
  if (!ts || ts == 0) return '';
  var d = new Date(Number(ts) * 1000);
  var y = d.getFullYear();
  var m = (d.getMonth() + 1 < 10 ? '0' : '') + (d.getMonth() + 1);
  var day = (d.getDate() < 10 ? '0' : '') + d.getDate();
  return y + '-' + m + '-' + day;
}

Page({

  data: {
    id: 0,
    savestatus: 0,
    title: '',
    jobinfo: {},
    publisherinfo: {},
    apply_count: 0,
    userRole: 0,
    markers: [],
    hasMap: false,
    mapLat: 0,
    mapLng: 0
  },

  onLoad: function (e) {
    var that = this;

    that.setData({ userRole: parseInt(wx.getStorageSync('user_role') || 0) });

    wx.showShareMenu({
      withShareTicket: true,
      menus: ['shareAppMessage', 'shareTimeline']
    })

    if (e) {
      if (e.hasOwnProperty("scene")) {
        var scene = decodeURIComponent(e.scene);
        var uid_array = scene.split('=');
        that.data.id = parseInt(uid_array[1]);
      } else {
        if (that.data.id > 0) {
          var id = that.data.id;
        } else {
          var id = e.id;
          that.data.id = e.id;
        }
      }
    }

    var params = { id: that.data.id };

    zwjobdetail.getZwJobDetailData((data) => {

      console.log('zwjobdetail response:', JSON.stringify(data));

      if (data && data.status == 0) {

        wx.setNavigationBarTitle({
          title: data.jobinfo.jobtitle,
        })
        that.data.title = data.jobinfo.jobtitle;
        that.data.savestatus = data.jobinfo.savestatus;

        var jobinfo = data.jobinfo;

        // 格式化时间戳为日期字符串
        if (jobinfo.createtime && !isNaN(jobinfo.createtime)) {
          jobinfo.createtime_fmt = formatDate(jobinfo.createtime);
        } else {
          jobinfo.createtime_fmt = jobinfo.createtime || '';
        }
        if (jobinfo.endtime && !isNaN(jobinfo.endtime)) {
          jobinfo.endtime_fmt = formatDate(jobinfo.endtime);
        } else {
          jobinfo.endtime_fmt = jobinfo.endtime || '';
        }

        // 结算方式文本
        var settleMap = { '1': '日结', '2': '周结', '3': '月结', '4': '完工结' };
        jobinfo.settle_text = settleMap[String(jobinfo.settle_type)] || '面议';

        // 性别文本
        if (jobinfo.sex == 1) {
          jobinfo.sex_text = '限男';
        } else if (jobinfo.sex == 2) {
          jobinfo.sex_text = '限女';
        } else {
          jobinfo.sex_text = '男女不限';
        }

        // 工价显示
        if (jobinfo.hourly_rate && jobinfo.hourly_rate != '0.00' && Number(jobinfo.hourly_rate) > 0) {
          jobinfo.price_text = jobinfo.hourly_rate;
          jobinfo.price_unit = '元/小时';
        } else if (jobinfo.money && jobinfo.money != '0' && jobinfo.money != '面议') {
          jobinfo.price_text = jobinfo.money;
          jobinfo.price_unit = '';
        } else {
          jobinfo.price_text = '面议';
          jobinfo.price_unit = '';
        }

        // 工作地址（后端已做降级，前端再兜底）
        if (!jobinfo.job_address && jobinfo.companyinfo) {
          jobinfo.job_address = jobinfo.companyinfo.address || '';
        }

        // 行业分类
        jobinfo.industry = (jobinfo.companyinfo && jobinfo.companyinfo.companycate) ? jobinfo.companyinfo.companycate : '';

        var markers = [];
        var hasMap = false;
        // 优先用岗位独立地址坐标，其次用公司地址坐标
        var lat = Number(jobinfo.job_lat);
        var lng = Number(jobinfo.job_lng);
        if (!lat || !lng) {
          if (jobinfo.companyinfo) {
            lat = Number(jobinfo.companyinfo.lat);
            lng = Number(jobinfo.companyinfo.lng);
          }
        }
        if (lat && lng) {
          hasMap = true;
          markers = [{
            id: 1,
            latitude: lat,
            longitude: lng,
            width: 30,
            height: 30
          }];
        }

        that.setData({
          jobinfo: jobinfo,
          savestatus: jobinfo.savestatus,
          publisherinfo: data.publisherinfo,
          apply_count: data.apply_count,
          markers: markers,
          hasMap: hasMap,
          mapLat: lat,
          mapLng: lng
        });

      } else {
        console.log('zwjobdetail error:', data);
        wx.showToast({
          title: (data && data.msg) ? data.msg : '加载失败',
          icon: 'none',
          duration: 5000
        })
      }

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, params);

  },

  doSendjob: function (e) {
    var that = this;

    // 发布者不能报名
    if (parseInt(wx.getStorageSync('user_role') || 0) == 2) {
      wx.showToast({
        title: '发布者不能报名哦',
        icon: 'none'
      });
      return;
    }

    var companyid = that.data.jobinfo.companyid;
    var params = { jobid: that.data.id, companyid: companyid };
    var params2 = {};

    token.verify(
      user.checkBind((data) => {

        if (data.isbind) {

          zwjobdetail.sendJob((data) => {

            if (data.status == 3) {

              wx.showModal({
                title: '提示',
                content: data.msg,
                showCancel: false,
                success: function () {
                  wx.navigateTo({
                    url: '/pages/mynote/index',
                  })
                }
              })
              return

            } else {

              wx.showToast({
                title: data.msg,
                icon: 'none',
                duration: 2000
              })

            }
          }, params);

        } else {

          wx.navigateTo({
            url: "/pages/register/index"
          })

        }

      }, params2)

    );
  },

  doSavejob: function (e) {
    var that = this;
    var companyid = that.data.jobinfo.companyid;
    var params = { jobid: that.data.id, companyid: companyid };
    token.verify(
      zwjobdetail.jobSave((data) => {

        if (data.status == 0) {
          that.setData({
            savestatus: 1
          });
        } else {
          that.setData({
            savestatus: 0
          });
        }
      }, params)
    )
  },

  goMap: function (e) {
    var that = this;
    var lat = that.data.mapLat;
    var lng = that.data.mapLng;
    if (lat && lng) {
      var addr = that.data.jobinfo.job_address || (that.data.jobinfo.companyinfo ? that.data.jobinfo.companyinfo.address : '');
      wx.openLocation({
        latitude: lat,
        longitude: lng,
        scale: 16,
        name: that.data.jobinfo.jobtitle || '',
        address: addr
      })
    }
  },

  onReady: function () { },
  onShow: function () { },
  onHide: function () { },
  onUnload: function () { },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.onLoad({ id: this.data.id });
  },

  onReachBottom: function () { },

  onShareAppMessage: function () {
    var that = this;
    return {
      title: that.data.title,
      path: '/pages/zwjobdetail/index?id=' + that.data.id
    }
  }
})
