// pages/usercard/index.js
import { Usercard } from '../../model/usercard-model.js';
var usercard = new Usercard();


Page({
  data: {
    cardType: 'applicant', // applicant 或 publisher
    card: {},
    isEditing: false,
    editSchool: '',
    editMajor: '',
    editLicense: '',
    phoneUnlocked: false,
    unlockedPhone: ''
  },

  onLoad: function (options) {
    var that = this;
    var type = options.type || 'applicant';
    var uid = options.uid || '';
    var companyid = options.companyid || '';

    that.setData({
      cardType: type,
      targetUid: uid,
      targetCompanyid: companyid
    });

    if (type == 'publisher') {
      wx.setNavigationBarTitle({ title: '发布者名片' });
      that.loadPublisherCard(companyid);
    } else {
      wx.setNavigationBarTitle({ title: '个人名片' });
      that.loadApplicantCard(uid);
    }
  },

  loadApplicantCard: function (uid) {
    var that = this;
    var params = {};
    if (uid) {
      params.uid = uid;
    }

    usercard.getApplicantCard(function (data) {
      if (data.status == 0) {
        that.setData({
          card: data.card,
          editSchool: data.card.school || '',
          editMajor: data.card.major || ''
        });
      } else {
        wx.showToast({ title: data.msg, icon: 'none' });
      }
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, params);
  },

  loadPublisherCard: function (companyid) {
    var that = this;
    var params = {};
    if (companyid) {
      params.companyid = companyid;
    }
    var ctoken = wx.getStorageSync('ctoken');
    if (ctoken) {
      params.ctoken = ctoken;
    }

    usercard.getPublisherCard(function (data) {
      if (data.status == 0) {
        that.setData({
          card: data.card,
          editLicense: data.card.business_license || ''
        });
      } else {
        wx.showToast({ title: data.msg, icon: 'none' });
      }
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, params);
  },

  // 切换编辑模式
  toggleEdit: function () {
    this.setData({ isEditing: !this.data.isEditing });
  },

  // 输入事件
  onSchoolInput: function (e) {
    this.setData({ editSchool: e.detail.value });
  },
  onMajorInput: function (e) {
    this.setData({ editMajor: e.detail.value });
  },

  // 保存报名者名片
  saveApplicantCard: function () {
    var that = this;
    var params = {
      school: that.data.editSchool,
      major: that.data.editMajor
    };

    usercard.saveApplicantCard(function (data) {
      if (data.status == 0) {
        wx.showToast({ title: '保存成功', icon: 'success' });
        that.setData({ isEditing: false });
        that.loadApplicantCard('');
      } else {
        wx.showToast({ title: data.msg, icon: 'none' });
      }
    }, params);
  },

  // 上传营业执照
  uploadLicense: function () {
    var that = this;
    wx.chooseImage({
      count: 1,
      sizeType: ['compressed'],
      sourceType: ['album', 'camera'],
      success: function (res) {
        var tempFilePath = res.tempFilePaths[0];
        wx.showLoading({ title: '上传中...' });

        wx.uploadFile({
          url: getApp().globalData.baseUrl + 'v1.Usercard/uploadImg',
          filePath: tempFilePath,
          name: 'file',
          header: {
            'token': wx.getStorageSync('token')
          },
          success: function (uploadRes) {
            var result = JSON.parse(uploadRes.data);
            if (result.imgpath) {
              that.setData({ editLicense: result.imgpath });
              wx.showToast({ title: '上传成功', icon: 'success' });
            }
          },
          complete: function () {
            wx.hideLoading();
          }
        });
      }
    });
  },

  // 保存发布者名片
  savePublisherCard: function () {
    var that = this;
    var ctoken = wx.getStorageSync('ctoken');
    var params = {
      ctoken: ctoken,
      business_license: that.data.editLicense
    };

    usercard.savePublisherCard(function (data) {
      if (data.status == 0) {
        wx.showToast({ title: '保存成功', icon: 'success' });
        that.setData({ isEditing: false });
        that.loadPublisherCard(that.data.targetCompanyid);
      } else {
        wx.showToast({ title: data.msg, icon: 'none' });
      }
    }, params);
  },

  // 解锁手机号（临时通话，消耗1章鱼币）
  unlockPhone: function () {
    var that = this;

    wx.showModal({
      title: '临时通话',
      content: '解锁手机号需消耗1章鱼币，确认解锁？',
      success: function (res) {
        if (res.confirm) {
          var params = {
            target_uid: that.data.card.uid || that.data.targetUid,
            target_type: that.data.cardType
          };
          if (that.data.cardType == 'publisher') {
            params.companyid = that.data.card.companyid || that.data.targetCompanyid;
          }

          usercard.unlockPhone(function (data) {
            if (data.status == 0) {
              that.setData({
                phoneUnlocked: true,
                unlockedPhone: data.phone
              });
              wx.makePhoneCall({
                phoneNumber: data.phone,
                fail: function () {
                  console.log('取消拨打');
                }
              });
            } else if (data.status == 2) {
              // 章鱼币不足
              wx.showModal({
                title: '余额不足',
                content: '章鱼币余额不足，是否前往充值？',
                success: function (modalRes) {
                  if (modalRes.confirm) {
                    wx.navigateTo({ url: '/pages/mymoney/index' });
                  }
                }
              });
            } else {
              wx.showToast({ title: data.msg, icon: 'none' });
            }
          }, params);
        }
      }
    });
  },

  // 直接拨打已解锁的手机号
  callPhone: function () {
    var that = this;
    if (that.data.unlockedPhone) {
      wx.makePhoneCall({
        phoneNumber: that.data.unlockedPhone,
        fail: function () {
          console.log('取消拨打');
        }
      });
    }
  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    if (this.data.cardType == 'publisher') {
      this.loadPublisherCard(this.data.targetCompanyid);
    } else {
      this.loadApplicantCard(this.data.targetUid);
    }
  },

  onShareAppMessage: function () {
    var that = this;
    var title = that.data.cardType == 'publisher' ? '企业名片' : '个人名片';
    var path = '/pages/usercard/index?type=' + that.data.cardType;
    if (that.data.cardType == 'publisher') {
      path += '&companyid=' + (that.data.card.companyid || '');
    } else {
      path += '&uid=' + (that.data.card.uid || '');
    }
    return { title: title, path: path };
  }
})
