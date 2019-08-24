/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function _get(key)
{
    var data = {};
    var group = location.href.match(/[?&](.+?)=([^&]*)/g);
    if (group && group.length) {
        for (var i in group) {
            var str = group[i].substr(1);
            var g = str.split("=");
            if (g && g.length == 2) {
                g[0] = g[0].replace("&", "");
                if (key && key == g[0]) {
                    return decodeURIComponent(g[1]);
                } else {
                    data[g[0]] = decodeURIComponent(g[1]);
                }
            }
        }
    }

    return key ? "" : data;
}

function _serialize(data)
{
    var s = "";
    for (var i in data) {
        s += "&" + i + "=" + encodeURIComponent(data[i]);
    }
    return s;
}

if (typeof localStorage == 'undefined')
{
    var localStorage = {};
}

$(function () {
    if (typeof localStorage["_url"] != 'undefined') {
        localStorage["_back_url"] = localStorage["_url"];
    }
    localStorage["_url"] = location.href;
})

function _go_back() {
    if (typeof localStorage["_back_url"] != 'undefined') {
        location.href = localStorage["_back_url"];
    } else {
        history.go(-1);
    }
}

function _pagination($node, page_no, maxPage)
{
    var html = "";
    if (maxPage > 1) {
        if (maxPage > 3 && page_no == maxPage) {
            html += '<li><a data-ci-pagination-page="1" rel="start">«</a></li>';
        }

        if (page_no != 1) {

            html += '<li><a data-ci-pagination-page="' + (page_no - 1) + '" rel="prev">上一页</a></li>';
        }

        if (page_no > 2) {
            html += '<li><a data-ci-pagination-page="' + (page_no - 2) + '">' + (page_no - 2) + '</a></li>';
        }

        if (page_no > 1) {
            html += '<li><a data-ci-pagination-page="' + (page_no - 1) + '">' + (page_no - 1) + '</a></li>';
        }

        for (var i = 0; i < 3 && page_no + i <= maxPage; i++)
        {
            if (i == 0) {
                html += '<li class="active"><a href="#">' + (page_no + i) + '</a></li>';
            } else {
                html += '<li><a data-ci-pagination-page="' + (page_no + i) + '">' + (page_no + i) + '</a></li>';
            }
        }
        if (page_no < maxPage) {
            html += '<li><a data-ci-pagination-page="' + (page_no + 1) + '" rel="next">下一页</a></li>';
        }
        if (maxPage > 3) {
            if (page_no == 1) {
                html += '<li><a data-ci-pagination-page="' + maxPage + '" rel="start">»</a></li>';
            }
        }
    }
    $node.html(html);
}

$(document).delegate("a[data-ci-pagination-page]", "click", function () {
    var $this = $(this);
    var page_no = $this.data("ci-pagination-page");
    var data = _get();
    data['page_no'] = page_no;
    location.href = location.pathname + "?" + _serialize(data);
});