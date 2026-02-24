import { Company } from '../../model/company-model.js';

var company  = new Company();
Page({
  data: {
 
    TabCur: 1,
    MainCur: 0,
    VerticalNavTop: 0,
    list: [],
    load: true,
    cateid:0,
    priceid:0,
    edu:'',
    express:'',
    sex:-1,
    special:'',
    loadmore:true

  },
  onLoad() {
    var that = this;
    wx.setNavigationBarTitle({
      title: '企业搜索',
    })
    
    wx.showLoading({
      title: '加载中...',
      mask: true
    });
  






  },

  toCompanyDetial: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/companydetail/index?id=" + id
    })
  },




  bindSave: function (e) {
    var that = this;
    var keyword = e.detail.value.keyword;

    if (keyword == "") {
      wx.showModal({
        title: '提示',
        content: '请输入相关信息',
        showCancel: false
      })
      return
    }


    var params = {keyword:keyword};
    company.getSearchCompany((data) => {

      if(data.companylist.length>0)
      {
      that.setData({
        companylist:data.companylist,
        loadmore:true
      }); 
    }else{
      that.setData({
        companylist:data.companylist,
        loadmore:false
      }); 
      
    }

    },params)

  },
  onReady() {
    wx.hideLoading()
  }
})