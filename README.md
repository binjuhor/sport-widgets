# GSA Website

## Project setup
```
npm install
```

### Compiles and hot-reloads for development

To run project in development mode you need to start the project with docker see below how to run project with docker
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

### Run project with docker

Just run docker container with command: `docker-composer up -d --build` then you can check the embed widget on `http://localhost:8081/` <- `this site demo for embed widget` we need to run `yarn build` before check this embed widget because I loaded the bundled js file from the dist folder.

Check the site with original VueJs project on `http://localhost:8080/` <- `this site my idea will work for GSA website`

### Challenge

- [x] Create a VueJs project
- [x] Create a VueJs component
- [x] Create an embed widget (web component)
- [x] All widgets should be server side render (rendered HTML by PHP)
- [x] Dynamically load only necessary assets (js and css)

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).
