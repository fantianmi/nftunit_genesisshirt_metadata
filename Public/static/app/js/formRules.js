export default {
  numberAndLetter: /^[0-9a-zA-Z]*$/, // 账号，可以为数字和字母
  phoneOrEmail: /(^1[34578]{1}\d{9}$)|(^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$)/,
  phone: /^1[3456789]{1}\d{9}$/, // 手机号
  email: /^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/, // 邮箱地址
  integer: /^(0|[1-9][0-9]*)$/, // 是否整数
  number: /^\d*$/, // 是否数字
  letter: /^[a-zA-Z]*$/, // 纯字母
  // qq: /^[1-9][0-9]{4,9}$/gim,
  chinese: /[\u4e00-\u9fa5]/, // 是否中文
  code: /^[1-9]\d{5}$/g, // 邮政编码
  IdCard: /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/, // 身份证
  pwd: /^[_a-zA-Z0-9]+$/, // 密码(包含数字字母大小写下划线)
  pwdStartWithLetter: /^[a-zA-Z]+[0-9a-zA-Z]*$/, // 密码首位为字母
  pwdStartWithUpperCase: /^[A-Z]+[0-9a-zA-Z]*$/, // 密码首字母为大写字母
  ip: /(\d+)\.(\d+)\.(\d+)\.(\d+)/, // ip地址
  newPassword: /^(?=.*\d)(?=.*[a-zA-Z]).{8,16}$/,
  // newPassword: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/, // 数字+字母大小写 8-16位
  password: /^[0-9a-zA-Z]{6,16}$/, // 数字/字母大小写 8-16位
  wechat: /^[a-zA-Z]([-_a-zA-Z0-9]{5,19}){1}$/, // 首位为字母 6-20位字母、数字、下划线
  //password: /^(?![\d]+$)(?![a-zA-Z]+$)(?![^\da-zA-Z]+$).{8,16}$/, //正则表达式 8-16位字符；数字、字母、特殊字符（除空格），至少其中两种组合
  bankCodeReg: /^([0-9])\d{15,20}$/ // 银行卡验证
}
