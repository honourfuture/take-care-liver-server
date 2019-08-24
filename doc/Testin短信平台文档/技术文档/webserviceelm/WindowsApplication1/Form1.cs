using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Web;


namespace WindowsApplication1

{

   
    public partial class Form1 : Form
    {

        zzsms.info zz = new zzsms.info();

       
        public Form1()
        {
            InitializeComponent();
        }


         public static string UrlEncode(string str)
        {
            StringBuilder sb = new StringBuilder();
            byte[] byStr = System.Text.Encoding.Default.GetBytes(str);
            for (int i = 0; i < byStr.Length; i++)
            {
                sb.Append(@"%" + Convert.ToString(byStr[i], 16));
            }
            
            return (sb.ToString());
        }

        private void button1_Click(object sender, EventArgs e)
        {
            string res="";

         
            res = zz.sendSMS("ZXHD-CRM-0100-XXXXXX", "密码",
                            "139107×××××"
                                    , UrlEncode("银行;理财%"),
                                          "", "1", "", "1", "", "4");

           

         //   MessageBox.Show(HttpUtility.UrlEncode("㎡窳憑櫁珺", System.Text.Encoding.GetEncoding("GBK")));
          if (res.Equals("0"))
          {
              MessageBox.Show("succ");

          }
          else
          {
              MessageBox.Show("fail" + res);
          }
        }

        private void button2_Click(object sender, EventArgs e)
        {
           MessageBox.Show( zz.getbalance("ZXHD-CRM-0100-******", "密码"));
          
        }

        private void button3_Click(object sender, EventArgs e)
        {
            //

            MessageBox.Show(  zz.register("ZXHD-CRM-0100-XXXXXX", "自己帐户的密码", " 企业名称", "简称", "地址", 
                "电话", "联系人", "email", "fax", "邮编", "手机")); 
            //注册（在帐户第一次使用时执行一次该方法就可以，参数个数以实际方法为准）
        }

        private void button4_Click(object sender, EventArgs e)
        {
            MessageBox.Show(zz.getmo("ZXHD-CRM-0100-******", "密码"));//返回结果按接口文档说明解析
        }

        private void button5_Click(object sender, EventArgs e)
        {
            MessageBox.Show(zz.getReport("ZXHD-CRM-0100-******", "密码"));//返回结果按接口文档说明解析
        }
    }
}