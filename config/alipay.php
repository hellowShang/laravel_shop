<?php
return [
    //应用ID,您的APPID。
    'app_id' => "2016092500595340",

    //商户私钥
    'merchant_private_key' => "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCiQf5FN3oGLgvqvfNtxJzKPTN4Gp1x6qBQGnehLmjyYI/8h0feGASgNOAXA5u3g6D4FiKlJd/cZYC97h4sbB0QPh2BFNj5cD/3GE7UHt6rXfT7cWRezY2DNZ6a6pH7y8okGJYgq2uTcOAsXSkDZrzibG82amDLj9fNdxwiJcopScLwkvbPHmMLx620CGqjSAoWHPqaQgCYzeTi1X64GoVix+xE0hTNd8p/HBLQsADBJnO5Ao6nUUnac5rMhuDiXGE5GoEARQi+VNjniFc3kFbrEuJnBEq5JciuUzt7nXgZkoR+QJBtnBFctB+Fafb33YywQL+Expgg+apgaQTTgItJAgMBAAECggEAKLjnV/fUaDimRQPnVGVD9H3nrP0BBtYb5f6+h3VeYXZarMZHAzaD8rFSjHQbYLNoctsbVtdql6Xh6ckZSYzYvnrYbM0Op4vEgf9XyWT1/YxXL8QFrMVp/sQ2SisU+FztHqINC6X6Gcb6fCflYzUlsq4EO2P3Zx9yyd5a8TcKxdGW7R7VEdhEizT7DYR/6tNoBt/qXfEiWqzpni4s0tJrMfkAjeGGJQFH4qKWW/HgZVGogNDSypX+U6zbF7zTuNo5aEZde0SPSLUUHbdPkC03rgpBbqyWq2cT2s/0LFeRmn7ncWIhXZzRXKouSV1rv9bYUGrVrueXN6mUS/diKLMxAQKBgQDWQkyX9QN6L1kyFPcoV+VH8a44FsgjgjGdtEVVzSJhvinRRQMTZYkpR2TWbC7fcc/YHp9XUuS9am2qq+Ia8fTfig6lway6Eif7n8aHSjnPNsjswqxwmhYFsx+lF/Sb3jTyduq0sH87L7YJluVJXLr5d1mvG4yaRIcFgOSG9VYHOwKBgQDB3j9dGSaDs7Ef8MFcc+zskOKJxMqLOTyVs6vhLrdsSmeHd86xnZtBCDzS0/DAU4owu1zvLu7mb1nhHUh0azTrO4BPw4sxlDNjdmV3KCiWOL3D8lHKzmlsSlU40A1/z4dgd78ncm7IVOgANg5PhC8mCQpUvfAvLh3J5FD44tB3SwKBgQCuaAt3Cl+JPy+I/93sfhfKB2X8jDbgCKOgrtRdsnyROb1KdrW5PUZ4ToA7tpY1FqzMTKkB6Rz/ProEWqPTsS7WI8gmj0+tqwfW4Ek3aWlDTmIhr5m0kwjoHNXeinB3zFonIDuPV7hJkl3I0obTv+MHHGrpijBahvuttCzR2rTrbwKBgDYsLVaNdZatupvrQYzdE+JS5gqLQ/G9b7GBQFEvJbAL5nwTPV2iZcS5UY20DX0gmufdIy3u1nDrwpjF/v4RCvuEZ+liAd47xFRRvC6cuSKo/lXnu3VJUmohJ0k10d/aFMEfFPon3s758s5ETKFplWcydYGShbAZWdeVhg7WClybAoGBAMSa3uwavEQdM4rfSogW4tLgUX+BGkiv2f6QdWmfXW9bRXnDQnTr2F/xWpR1cd1JSmSb+5dIFUZyYCrjOXkVKgPaCQVDA2Lli5VfJguDtOFTX0GQwcfvjddrWgKnwWSsy6y1L0JojbkGJGM7fbZB4eOX9DJPYiyA86D8TCcdZ1i5",

    //异步通知地址
    'notify_url' => "http://www.blog.com/car/notifypay",

    //同步跳转
    'return_url' => "http://www.blog.com/car/returnpay",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAowZUolMLgfpS4PSzxJG38CTSb2haBQadX98WXMMmUuWfm9LyE3ny7HBkO+ajFfsU6hRBV71EG864fLlqw3CUhjOgc4lYDD7+JeSgzLOPKoS+aCam5u21WCcZe6uBGuksLxi3pAFSkK1/ERijHj57Yp7/FalZhVnG33IxSxx3ltqEx7AyynBXOcaPFdvOJ2fslt0cekymO/XiewFndTNwiNfp7MwQI5vBr/7CuhZe903UBaZ3enfTVHlJBR0bN10B7LV+VEYey+wWa1Faq+d8BB2Fm0J93FB8YGQF4yLxvXSy10Idb4cffE6xBNOvMXWc3IaBLtcRk+qJt17xC3d3HQIDAQAB",
];