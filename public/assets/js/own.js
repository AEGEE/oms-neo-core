const showError = (err, description = "Could not process action: ") => {
  console.log(err);
  let message = 'Unknown cause';

  if (err && err.message) {
    message = err.message;
  } else if (err && err.data && err.data.message) {
    message = err.data.message;
  }

  $.gritter.add({
    title: 'Error',
    text: `${description} ${message}`,
    sticky: false,
    time: 8000,
    class_name: 'my-sticky-class',
  });
};



const showSuccess = (message) => {
  $.gritter.add({
    title: 'Success',
    text: message,
    sticky: false,
    time: 8000,
    class_name: 'my-sticky-class',
  });
}

const convertToCsv = (data) => {
  const escape = (str) => {
    if(!str)
      return "";
    var result = str.toString().replace(/"/g, '""');
    if (result.search(/("|,|\n)/g) >= 0)
      result = '"' + result + '"';
    return result;
  }

  var labels = [];
  data.forEach((obj) => {
    for(var key in obj) {
      if(obj.hasOwnProperty(key) && !labels.find((label) => {return label == key;})) {
        labels.push(key);
      }
    }
  });

  var csvContent = "";

  csvContent += labels.map(escape).join(',') + "\n";

  csvContent += data.map((obj) => {
    var tmp = [];
    labels.forEach((label) => {
      tmp.push(escape(obj[label]));
    });
    return tmp.join(',');
  }).join('\n');
  return csvContent;
}

const infiniteScroll = ($http, vm, url, paramInjector) => {
  vm.infiniteScroll = {
    pageSize: 20,
    raceCounter: 0,
    search: "",
  }

  vm.resetData = () => {
    vm.infiniteScroll.block = false;
    vm.infiniteScroll.busy = false;
    vm.infiniteScroll.data = [];
    vm.infiniteScroll.page = 1;
    return vm.loadNextPage();
  }

  vm.loadNextPage = () => {
    vm.infiniteScroll.block = true;
    vm.infiniteScroll.busy = true;
    vm.infiniteScroll.raceCounter++;
    var localRaceCounter = vm.infiniteScroll.raceCounter;
    var params = {
      page: vm.infiniteScroll.page,
      per_page: vm.infiniteScroll.pageSize
    };
    if(paramInjector)
      params = paramInjector(params);
    var sendParams = {};
    for(var key in params) {
      if(params.hasOwnProperty(key) && params[key])
        sendParams[key] = params[key];
    }


    return $http({
      url: url,
      method: 'GET',
      params: sendParams
    }).then((response) => {
      if(localRaceCounter == vm.infiniteScroll.raceCounter) {
        vm.infiniteScroll.busy = false;
        if(response.data.data.length > 0) {
          vm.infiniteScroll.page++;
          vm.infiniteScroll.data.push.apply(vm.infiniteScroll.data, response.data.data);
          if(response.data.meta.current_page != response.data.meta.last_page)
            vm.infiniteScroll.block = false;
        }
      }
    }).catch((error) => {
      showError(error);
    });
  }

  vm.resetData();
}