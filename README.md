# hostkerdns
写这玩意儿主要是主机壳的dns解析管理不好用。<br>
跟主机壳API交互用了自己写的代理，避免跨域问题。<br>
API请求地址在<code>src/Common/HostkerAPI.ts</code>里修改<br>

# HostkerAPI跨域问题
js不能直接请求主机壳的api，有跨域问题，所以我写了个简单的php代理<br>
将<code>api</code>文件夹放到自己的网站根目录，修改项目的api地址即可<br>
服务器环境 apache2，php

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).
