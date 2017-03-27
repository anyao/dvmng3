function transData(a, idStr, pidStr, chindrenStr){    
    var r = [], hash = {}, tags = idStr, pid = pidStr, nodes = chindrenStr, i = 0, j = 0, len = a.length;    
    for(; i < len; i++){    
        hash[a[i][tags]] = a[i];    
    }    
    for(; j < len; j++){    
        var aVal = a[j], hashVP = hash[aVal[pid]];    
        if(hashVP){    
            !hashVP[nodes] && (hashVP[nodes] = []);    
            hashVP[nodes].push(aVal);    
        }else{    
            r.push(aVal);    
        }    
    }    
    return r;    
    }   