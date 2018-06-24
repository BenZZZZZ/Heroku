var pagePerms = {}, pagePermsCount = 0, pagePermsElement = {},
    getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'), sParameterName, i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    },getAllUrlParameter = function getAllUrlParameter() {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'), sParameterName, i, data = {};

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            data[sParameterName[0]] = sParameterName[1];

            // if (sParameterName[0] === sParam) {
            //     return sParameterName[1] === undefined ? true : sParameterName[1];
            // }
        }
        return data;
    }, getHashIdFromUrl = function getHashIdFromUrl() {
        return window.location.href.split('#')[1];
    }, getRoute = function getRoute() {
        var route = URL.replace(BASEURL, "");
        route = route.split('#')[0];
        route = route.split('?')[0];
        // console.log(route);
        // console.log(routeList);
        if (routeList[route]) {
            route = routeList[route];
        }
        // route = route + '.php';
        return route;
    }, route = function route(e, route) {
        sL();
        // alert(route);
        $(e).load(route + '.html', {limit: 25}, 
            function (responseText, textStatus, req) {
                if(responseText.indexOf('<!D') != -1){
                    alert('ROUTE ' + route + ' not Found');
                }
            }
        );
        hL();
    }, gFD = function gFD(element){//getFormData
        var inputs = $(element).find(formFields), data = {}, checkbox = {}, i = 0;
        inputs.each(function(){
            if($(this).attr('type') === 'checkbox'){
                if($(this).prop('checked')){
                    checkbox[i] = $(this).val();
                    data[$(this).attr('name')] = checkbox;
                    i++;
                }
            }else{
                data[$(this).attr('name')] = $(this).val();
            }
        });
        return data;
    }, rFD = function rFD(e){//resetFormData
        var inputs = $(e).find(formFields);
        inputs.each(function() {
            if($(this).attr('type') !== 'checkbox'){
                $(this).val("");
            }
        });
    }, fFD = function fFD(e, data) {//filFormData
        var inputs = e.find(formFields);
        inputs.each(function() {
            $(this).val(data.data[$(this).attr('name')]);
        });
    }, aC = function aC(method, url, data = false) {//ajaxCall
        var responseData;
        $.ajax(url, {
            type: method,
            async: false,
            data: data,
            dataType: 'application/json',
            success: function(data, status) {
                // responseData = $.parseJSON(data.responseText);
                responseData = data.responseText;
            },
            error: function(data, status) {
                // responseData = $.parseJSON(data.responseText);
                responseData = data.responseText;
            }
        });
        try {
            responseData = $.parseJSON(responseData);
        }catch(err) {
            alert(err);
        }
        return responseData;
    }, sL = function sL() {//showLoader
        $('#loader').removeClass('hide');
    }, hL = function hL() {//hideLoader
        $('#loader').addClass('hide');
    }, filTabPagDataFnUrl = function filTabPagDataFnUrl(e) {//filTabPagDataFunctionUrl
        var page = e.attr('data-p'), limit = e.attr('data-l'), url;
        e.attr(
            'data-api',
            Mustache.to_html(e.attr('data-api'), getAllUrlParameter())
        ); 
        url = APIURL + e.attr('data-api') + '?p=' + page + '&l=' + limit;
        return url;
    }, filTabPagDataFn = function filTabPagDataFn(e, url) {//filTabPagDataFunction
        sL();
        var tableBodyTag = e.find('.filTabData').find('tbody'),
            tableHeadTag = e.find('.filTabData').find('thead th input,thead th select'),
            postData = {}, tempData = {}, tableBodyTemplate, tableBodyTemp;
        tableBodyTag.find('.generated').each(function(){
            $(this).remove();
        });
        tableHeadTag.each(function(){
            // console.log($(this).val());
            tempData[$(this).parents('th').attr('data-name')] = $(this).val();
        });
        console.log(tempData);
        postData['search'] = tempData;
        tableBodyTemplate = tableBodyTag.html().trim();
        tableBodyTemplate = tableBodyTemplate.split("template").join(' generated');
        tableBodyTemplate = tableBodyTemplate.split("hide").join('');
        results = aC('POST', url, postData);
        rDF(results);
        $.each(results.data.data, function(k, v){
            tableBodyTemp = tableBodyTemplate;
            tableBodyTemp = Mustache.to_html(tableBodyTemp, v);
            // $.each(v, function(k1, v1){
            //     tableBodyTemp = tableBodyTemp.split("{{" + k1 + "}}").join(v1);
            // });
            tableBodyTag.append(tableBodyTemp);
        });
        tableBodyTag.find('.template').addClass('hide');
        tabPag(
            e.find('.filPagData'), results.data.count, results.data.limit,
            results.data.page
        );
        hL();
    }, tabPag = function tabPag(e, count, limit, page) {//tablePagination
        var pagTemplate, pagTemp, pages;
        e.find('.generated').each(function(){
            $(this).remove();
        });
        pagTemplate = e.html().trim();
        if(pagTemplate !== undefined){
            pagTemplate = pagTemplate.split("template").join(' generated');
            pagTemplate = pagTemplate.split("hide").join('');
            pages = Math.ceil(count / limit);
            for(var i = 1; i <= pages; i ++){
                pagTemp = pagTemplate;
                pagTemp = Mustache.to_html(pagTemp, {
                    class: (page == i) ? "active filTabPag" : "filTabPag",
                    page: i
                });
                // if(page == i){
                //     pagTemp = pagTemp.split("{{class}}").join('active filTabPag');
                // }else {
                //     pagTemp = pagTemp.split("{{class}}").join('filTabPag');
                // }
                // pagTemp = pagTemp.split("{{page}}").join(i);
                e.append(pagTemp);
            }
            e.find('.template').addClass('hide');
        }
    }, filTemplateDataFn = function filTemplateDataFn(e, url) {//filTabPagDataFunction
        sL();
        var template, temp, pages;
        e.find('.generated').each(function(){
            $(this).remove();
        });
        template = e.html().trim();
        if(template !== undefined){
            template = template.split("template").join(' generated');
            template = template.split("hide").join('');
            results = aC('POST', url, {});
            rDF(results);
            $.each(results.data.data, function(k, v){
                temp = template;
                temp = Mustache.to_html(temp, v);
                // $.each(v, function(k1, v1){
                //     temp = temp.split("{{" + k1 + "}}").join(v1);
                // });
                e.append(temp);
            });
            e.find('.template').addClass('hide');
        }
        hL();
    }, redir = function redir(redir) {//redirect
        if(getRoute() !== redir){
            window.location.href = BASEURL + redir;
        }
    }, rDF = function rDF(results) {//returnDataFunctions
        sL();
        if(results.status === "true"){
            if(results.alert){
                alert(results.alert);
            }
            if(results.redir){
                redir(results.redir);
                // window.location.href = results.redir;
            }
            $('.route').find('div').removeClass('hide');
        }else{
            if(results.alert){
                alert(results.alert);
            }
            if(results.redir){
                redir(results.redir);
                // window.location.href = results.redir;
            }
        }
        hL();
    }, URL = window.location.href, ROUTE = getRoute();

$(document).on('keyup', 'input', function(e){
    if (e.keyCode === 13) {
        console.log($(this).parents('form').find('button.formSubBtn').click());
    }
});

$(document).on('click', '.formSubBtn', function(){
    sL();
    var form = $(this).parents('form'), formElementBlocks = form.find('div'),
        data = gFD(form), url;
    $(this).attr(
        'data-api',
        Mustache.to_html($(this).attr('data-api'), getAllUrlParameter())
    );
    url = APIURL + $(this).attr('data-api');
    results = aC('POST', url, data);
    formElementBlocks.each(function(i){
        $(this).removeClass("has-error");
        $(this).find('span').text("");
    });
    if(results.status === "true"){
        if(results.alert){
            rFD(form);
            alert(results.alert);
        }
        if(results.redir){
            window.location.href = results.redir;
        }
    }else{
        if(results.alert){
            alert(results.alert);
        } else if(results.formErr){
            $.each(results.formErr, function(k, v){
                if(v.err){
                    form.find('[name='+k+']').parent('div').find('span').text(v.err);
                    form.find('[name='+k+']').parent('div').addClass('has-error');
                    // form.find('#' + k).parent('div').find('span').text(v.err);
                    // form.find('#' + k).parent('div').addClass('has-error');
                }
            });
        }
    }
    if($(this).attr('data-ref') !== undefined){
        var dataRef = '#' + $(this).attr('data-ref');
        url = filTabPagDataFnUrl($(dataRef));
        filTabPagDataFn($(dataRef), url);
    }
    hL();
});


$(document).on('click', '.btnClick', function(){
    sL();
    var url = APIURL + $(this).attr('data-api'),
    results = aC('POST', url, {});
    rDF(results);
    hL();
});


$(document).on('click', '.btnClickList', function(){
    sL();
    var url = APIURL + $(this).attr('data-api'), data = {}, result;
    data.id = $(this).attr('data-id');
    results = aC('POST', url, data);
    rDF(results);
    if($(this).attr('data-ref') !== undefined){
        var dataRef = '#' + $(this).attr('data-ref');
        url = filTabPagDataFnUrl($(dataRef));
        filTabPagDataFn($(dataRef), url);
    }
    hL();
});

$(document).on('click', '.clickModalBtn', function(){
    var modalId = $(this).attr('data-modal'), url, results;
    rFD($(modalId).find('form'));
    if($(this).attr('data-id') !== undefined && $(modalId).attr('data-api') !== undefined){
        url = APIURL + $(modalId).attr('data-api').split('{id}').join($(this).attr('data-id'));
        results = aC('GET', url);
        rDF(results);
        fFD($(modalId).find('form'), results);
    }
    $(modalId).modal('show');
});